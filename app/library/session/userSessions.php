<?php

namespace app\library\session;

use Phalcon\Di\Injectable,
    app\models\sessions,
    Phalcon\Exception,
    Phalcon\Filter;

class userSessions extends Injectable
    {
        public function registerSession($uid, $ajax = false)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $session = new \stdClass();
                $session->uid = (int)$uid;
                $session->key = (string)$uid;
                if ($mongo->sessions->insert($session)['ok'] == 1)
                    {
                        if ($ajax == true)
                            return json_encode(array('name' => 'user', 'value' => $uid, 'expire' => time() + 300000));
                        if (!$this->cookies->set('user', $uid, time() + 300000))
                            {
                                throw new Exception("Cookie can't be created.");
                            }
                    } else
                    {
                        throw new Exception("Session can't be created.");
                    }
            }

        public function deleteSession()
            {
                $userSessionData = $this->getUserSessionData();
                $this->cookies->set('user', '', -1);
                if (!$userSessionData)
                    {
                        return true;
                    } elseif ($userSessionData->delete() == false)
                    {
                        throw new Exception("Session can't be deleted.");
                    } else
                    {
                        return true;
                    }
            }

        private function getUserSessionData()
            {
                $filter = new Filter();
                $user = $this->cookies->get('user');
                $keyValue = $filter->sanitize($user->getValue(), 'string');
                return sessions::findFirst(array(array('key' => (string)$keyValue)));
            }

        public function getUserID()
            {
                $userSessionData = $this->getUserSessionData();
                return $userSessionData ? $userSessionData->uid : 0;
            }

        public function requiredLogin($json_encode = false)
            {
                $userSessionData = $this->getUserSessionData();
                if ($userSessionData)
                    {
                        return $userSessionData->uid;
                    } else
                    {
                        if ($json_encode == true)
                            {
                                $this->response->setJsonContent(array('errorLogin' => true));
                                $this->response->send();
                                exit;
                            }
                        $this->flash->error("You are required to log in.");
                        $this->dispatcher->forward(array(
                            'controller' => 'auth',
                            'action' => 'login'
                        ));
                    }
                return false;
            }
    }