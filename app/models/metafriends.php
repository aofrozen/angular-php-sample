<?php

namespace app\models;

use MongoDate;

class metafriends extends \Phalcon\Mvc\Collection
    {
        public $ts;
        public $uid;
        public $stats;
        public $limit;

    /*
     * Meta Friends
     *
     * Details
     *  a. request date
     *  b.
     */
        public function getSource()
            {
                return 'metafriends';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function build($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $metafriend = new \stdClass();
                $metafriend->stats = new \stdClass();
                $metafriend->stats->day = new \stdClass();
                $metafriend->limit = new \stdClass();
                $metafriend->ts = new MongoDate();
                $metafriend->uid = $uid;
                $metafriend->stats->friendCount = 0;
                $metafriend->stats->ts = new MongoDate();
                $metafriend->stats->day->friendCount = 0;
                $metafriend->stats->day->ts = new MongoDate();
                $metafriend->limit->FPD = 100; //Friend Per Day
                $metafriend->limit->friendCount = 2000;
                //server location
                //collection location
                if ($mongo->metafriends->insert($metafriend))
                    return true;
                return false;
            }

        public function _get($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $result = iterator_to_array($mongo->metafriends->find(array('uid' => (int)$uid)), false);
                if(count($result) > 0)
                    return $result[0];
                return array();
            }

        public function _updateFriendCount($uid, $uid2, $count)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->metafriends->update(array('uid' => array('$in' => array((int)$uid2, (int)$uid))), array('$inc' => array('stats.friendCount' => (int)$count)),  array('multiple' => true, 'upsert' => false));
            }
    }