<?php

namespace app\models;

use app\models\inc,
    app\library\dry\dry as d,
    MongoDate;

class users extends \Phalcon\Mvc\Collection
    {
        public $uid;
        public $oauthAccessToken;
        public $oauthProvider;
        public $oauthUID;
        public $disabledAccount;
        public $disabledAccountReason;
        public $email;
        public $password;
        public $passwordRecovery;
        public $ts;
        public $signupDate;
        public $completeSetup; //false or true
        public $verifiedMobileNumbers; //false or true
        public $permanentMobileNumbers; //permanent mobile numbers

        public function getSource()
            {
                return 'users';
            }


        public function beforeCreate()
            {
                /*
                if ($this->uid == null)
                    {
                        $result = (new inc())->_execute('users');
                        if ($result)
                            {
                                $this->uid = $result['next_id'];
                            } else
                            {
                                throw new \Exception('Failed to generate auto increment for users');
                            }
                        $this->ts = new MongoDate();
                    }
                */
            }

        public static function getOAuthUserID($oauthProvider, $oauthUID)
            {
                return users::findFirst(array(array("oauthUID" => (string)$oauthUID, "oauthProvider" => (string)$oauthProvider)));
            }

        public function getUserWithEmail($email)
            {
                return users::findFirst(array(array("email" => (string)$email)));
            }

        public function emailExists($email, $uid = null)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $user = iterator_to_array($mongo->users->find(array('email' => (string)$email))->limit(0), false);
                if(count($user) == 0)
                    return false;
                if($user[0]['uid'] == $uid)
                    return false;
                return true;
            }

        public function buildWithEmail($email, $password)
            {
                if(!$email || !$password)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $user = new \stdClass();
                $user->password = new \stdClass();
                $result = (new inc())->_execute('users');
                if ($result)
                    {
                        $user->uid = $result['next_id'];
                        if ($user->uid == 0)
                            throw new \Exception('Failed to generate auto increment for users [0].');
                    } else
                    {
                        throw new \Exception('Failed to generate auto increment for users');
                    }
                $user->ts = new MongoDate();
                $user->oauthProvider = null;
                $user->oauthUID = null;
                $user->email = (string)$email;
                $user->password = new \stdClass();
                $user->password->hash = (string)$password['hash'];
                $user->password->salt = (string)$password['salt'];
                $user->password->alg = (string)$password['alg'];
                $user->password->iterations = (int)$password['iterations'];
                $user->passwordRecovery = null;
                $user->disabledAccount = false;
                $user->disabledAccountReason = null;
                if ($mongo->users->insert($user)['ok'] == 1)
                    return $user;
                return false;
            }

        public function build($userProfile, $provider)
            {
                if(!$userProfile || !$provider)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $user = new \stdClass();
                $result = (new inc())->_execute('users');
                if ($result)
                    {
                        $user->uid = $result['next_id'];
                    } else
                    {
                        throw new \Exception('Failed to generate auto increment for users');
                    }
                $user->oauthProvider = $provider;
                $user->oauthUID = $userProfile->identifier;
                $user->email = null;
                $user->passwd = null;
                $user->passwordRecovery = null;
                $user->disabledAccount = false;
                $user->disabledAccountReason = null;
                if ($mongo->users->insert($user)['ok'] == 1)
                    return $user;
                return false;
            }

        public function passwordRecovery($email, $key, $newPassword)
            {

            }

        public function sendPasswordRecovery($email)
            {
                $user = $this->findFirst(array(array('email', $email)));
            }

        public function _get($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $user = iterator_to_array($mongo->users->find(array('uid' => (int)$uid), array('_id' => 0, 'email' => 1, 'text' => 1)), false)[0];
                if (!$user)
                    return array();
                return $user;
            }

        public function _set($uid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $user = $mongo->users->update(array('uid' => (int)$uid), array('$set' => $data));
                if (!$user)
                    return array();
                return $user;
            }
    }