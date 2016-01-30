<?php

namespace app\controllers;

use app\library\session\userSessions,
    \Phalcon\Mvc\View,
    app\models\users,
    app\models\profiles,
    app\models\metausers,
    app\library\tokumx\transaction,
    app\models\metajournals,
    app\models\metafriends,
    app\models\metanotifications,
    app\models\metaphotos,
    app\models\metaprofiles,
    app\models\privacy,
    \Phalcon\Exception,
    app\library\password\password;

class AuthController extends ControllerBase
    {
        public function initialize()
            {
                parent::initialize();
            }

        public function recoverAction()
            {
                $this->view->setVar('title', 'Recover Password');
                //AJAX
            }

        public function signupAction()
            {
                $this->view->setVar('title', 'Sign Up');
                $userSession = new userSessions();
                $_userID = $userSession->getUserID();
                if ($_userID) $this->response->redirect('/home/');
                if ($this->request->isPost())
                    {
                        $this->view->setRenderLevel(view::LEVEL_NO_RENDER);
                        $json = $this->request->getJsonRawBody();
                        $result = $this->createAccount($json->email, $json->password);
                        if(isset($result['uid']))
                            {
                                $userSession->registerSession($result['uid']);
                            }
                        $this->response->setJsonContent($result);
                        $this->response->send();
                        exit;
                    }
            }

        public function authAction()
            {
                if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done']))
                    {
                        \Hybrid_Endpoint::process();
                    }
            }

        public function accountSetupAction()
            {
                /*
                 * Check required steps before access anything
                 *
                 *
                 * Required:
                 * 1. Username
                 * 2. Text
                 * 3. Gender
                 * 4. Birthday
                 * 5. Country
                 * 6. City
                 * 7. State
                 *
                 * Optional:
                 * 1. Photo avatar
                 *
                 */
            }

        public function loginAction()
            {
                session_start();
                /*
                if ($this->detect->isMobile() || $this->detect->isTablet())
                    {
                        $this->view->pick('mobile/auth/login');
                    } else
                    {
                        $this->view->pick('auth/login');
                    }
                */
                $this->view->pick('auth/login');
                $userSession = new userSessions();
                $this->view->setVar('title', 'Login');
                //$this->view->setRenderLevel($this->view->setRenderLevel(View::LEVEL_LAYOUT));
                $_userID = $userSession->getUserID();
                if ($this->request->isPost() && $_userID)
                    {
                        $this->response->setJsonContent(array("redirect" => null, "success" => true));
                        $this->response->send();
                        exit;
                    }
                if ($_userID) $this->response->redirect('/home/');
                if ($this->request->isGet())
                    {
                        $provider = $this->request->get('provider');
                        if ($provider)
                            {
                                $adapter = $this->auth->authenticate($provider);
                                $userProfile = $adapter->getUserProfile();
                                $user_contacts = $adapter->getUserContacts();
                                $uid = self::accountExists($userProfile, $provider);
                                $adapter->logout();
                                if (!$uid)
                                    $uid = self::createSocialAccount($userProfile, $provider);
                                $userSession->registerSession($uid);
                                $this->response->redirect('/home/', true);
                                $this->response->send();
                                exit;
                                //Account Setup


                            } else
                            {
                                //missing provider
                                //$this->flash->error('Provider is missing.');
                            }
                    }
                if ($this->request->isPost())
                    {
                        $this->view->setRenderLevel(view::LEVEL_NO_RENDER);
                        $userSession = new userSessions();
                        $json = $this->request->getJsonRawBody();
                        $user = (new users())->getUserWithEmail($json->email);
                        if ($user == null)
                            {
                                $this->response->setJsonContent(array("redirect" => null, "success" => false, "alerts" => array('title' => '', 'message' => 'Either email or password is incorrect you entered.', 'type' => 'danger')));
                                $this->response->send();
                                exit;
                            }
                        $hash = (new password())->testPass((string)$json->password, (string)$user->password['salt'], (int)$this->config->password->length, (int)$user->password['iterations'], (string)$user->password['alg']);
                        if ($user && ($hash == $user->password['hash']))
                            {
                                $userSession->registerSession($user->uid);
                                $this->response->setJsonContent(array("redirect" => null, "success" => true));
                                $this->response->send();
                                exit;
                            } else
                            {
                                $this->response->setJsonContent(array("redirect" => null, "success" => false, "alerts" => array('title' => '', 'message' => 'Either email or password is incorrect you entered.', 'type' => 'danger')));
                                $this->response->send();
                                exit;
                            }
                    }
                session_write_close();
            }

        static private function accountExists($userProfile, $provider)
            {
                $user = users::findFirst(array(array('oauthUID' => $userProfile->identifier, 'oauthProvider' => $provider)));
                return $user->uid;
            }

        public function createAccount($email, $password)
            {

                //$t = new transaction();
                //$t->beginTransaction();
                if ((new users())->emailExists($email))
                    {
                        //$t->rollbackTransaction();
                        return array('success' => false, 'alerts' => array('type' => 'danger', 'title' => 'Sign Up Problems', 'message' => 'Email already exists. Go to login page.'));
                    }
                if (empty($email) || empty($password))
                    {
                        //$t->rollbackTransaction();
                        return array('success' => false, 'alerts' => array('type' => 'danger', 'title' => 'Sign Up Problems', 'message' => "Email or password can't be emptied."));
                    }
                $passwordData = (new password())->securePass($password, $this->config->password->alg, $this->config->password->length, $this->config->password->iterations); //$alg . ":" . $iterations . ":" . $salt . ":" . $hash;
                $user = (new users())->buildWithEmail($email, $passwordData);
                if (!$user)
                    {
                        //$t->rollbackTransaction();
                        return array('success' => false, 'alerts' => array('type' => 'danger', 'title' => 'Sign Up Problems', 'message' => "User account can't be created."));
                    }
                $uid = $user->uid;
                $profileData = null;
                if ($uid)
                    {
                        if ((new privacy())->build($uid) && (new metafriends())->build($uid) && (new metausers())->build($uid) && (new profiles())->build($uid, $profileData) && (new metaprofiles())->build($uid) && (new metausers())->build($uid) && (new metanotifications())->build($uid) && (new metaphotos())->build($uid))
                            {
                                //$t->commitTransaction();
                                return array('success' => true, 'uid' => $uid, 'redirect' => null);
                            } else
                            {
                                //$t->rollbackTransaction();
                                return array('success' => false, 'alerts' => array('type' => 'danger', 'title' => 'Sign Up Problems', 'message' => "Account can't be created."));
                            }
                    }
            }

        static private function createSocialAccount($userProfile, $provider)
            {
                //$t = new transaction();
                //$t->beginTransaction();
                $profileData['fName'] = $userProfile->firstName;
                $profileData['lName'] = $userProfile->lastName;
                switch ($provider)
                    {
                    case 'facebook':
                            $profileData['FacebookLink'] = $userProfile->profileURL;
                            break;
                    case 'twitter':
                            $profileData['TwitterLink'] = $userProfile->profileURL;
                            break;
                    case 'google':
                            $profileData['GoogleLink'] = $userProfile->profileURL;
                            break;
                    }
                $user = (new users())->build($userProfile, $provider);
                if (!$user)
                    throw new Exception("User account can't be created.");
                $uid = $user->uid;
                /*
                 * Build
                 * List: metausers, profiles, metanotification, metafriends, metaphotos, metajournals,
                 */
                if ((new privacy())->build($uid) && (new metafriends())->build($uid) && (new metausers())->build($uid) && (new profiles())->build($uid, $profileData) && (new metaprofiles())->build($uid) && (new metausers())->build($uid) && (new metanotifications())->build($uid) && (new metaphotos())->build($uid) && (new metajournals())->build($uid))
                    {
                       // $t->commitTransaction();
                    } else
                    {
                        //$t->rollbackTransaction();
                        throw new Exception("Account can't be created");
                    }
                return $uid;
            }

        public function logoutAction()
            {
                $userSession = new userSessions;
                $userSession->deleteSession();
                $this->response->redirect('/login', true);
                $this->response->send();
                exit;
            }

        public function changePasswordAction()
            {
                if ($this->request->isPost() && $this->request->isAjax())
                    {
                    }
            }
    }