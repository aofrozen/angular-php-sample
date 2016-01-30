<?php

namespace app\models;

use MongoDate;

class friends extends \Phalcon\Mvc\Collection
    {
        public $ts;
        public $uid;
        public $fid;

        public function getSource()
            {
                return 'friends';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function _exist($uid, $fid)
            {
                $friend = friends::findFirst(array(array('uid' => (int)$uid, 'fid' => (int)$fid)));
                if ($friend)
                    {
                        return true;
                    } else
                    {
                        return false;
                    }
            }

        public function _create($uid, $fid, $fidKey)
            {
                $mongo = $this->getDI()->getShared('mongo');
                if ($uid == $fid || $fid <= 0 || $fid == null)
                    return false;
                $friend = new \stdClass();
                $friend->ts = new MongoDate(); //$this->ts... is removed when phalcon bug is fixed
                $friend->uid = (int)$uid;
                $friend->fid = (int)$fid;
                $friend->fidKey = $fidKey;
                if ($mongo->friends->insert($friend)['ok'] == 1)
                    return true;
                return false;
            }

        public function _delete($uid, $fid)
            {
                $friend = friends::findFirst(array(array('uid' => (int)$uid, 'fid' => (int)$fid)));
                if (!$friend) return true;
                if ($friend->delete())
                    return true;
                return false;
            }

        public function _get($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $friends = $mongo->friends->find(array('uid' => (int)$uid));
                return iterator_to_array($friends, false);
            }
    }