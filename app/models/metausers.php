<?php

namespace app\models;

use MongoDate;

class metausers extends \Phalcon\Mvc\Collection
    {
        public $uid;
        public $signup;

        public function getSource()
            {
                return 'metausers';
            }

        public function build($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $metauser = new \stdClass();
                $metauser->signup = new \stdClass();
                $metauser->uid = $uid;
                $metauser->signup->ts = new MongoDate();
                $metauser->signup->ipAddress = ip2long($_SERVER['REMOTE_ADDR']);
                if ($mongo->metausers->insert($metauser)['ok'] == 1)
                    return true;
                return false;
            }
    }