<?php

namespace app\models;

use MongoDate;

class feedfollows extends \Phalcon\Mvc\Collection
    {
        public function getSource()
            {
                return 'feedfollows';
            }

        public function _create($uid, $followUid)
            {
                if(!$uid || !$followUid)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $feedfollow = new \stdClass();
                $feedfollow->ts = new MongoDate();
                $feedfollow->uid = (int)$uid;
                $feedfollow->followUid = (int)$followUid;
                $feedfollow->lastRead = new MongoDate();
                $feedfollow->orderId = 0;
                if($mongo->feedfollows->insert($feedfollow)['ok'] == 1)
                    return true;
                return false;
            }

        public function _delete($uid, $followUid)
            {
                if(!$uid || !$followUid)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->feedfollows->remove(array('uid' => (int)$uid, 'followUid' => (int)$followUid)))
                    return true;
                return false;
            }

        public function _getFeedFollows($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                if(!$uid)
                    return array();
                $results[0] = array('uid' => $uid, 'followUid' => $uid, 'lastRead' => 0);
                $results = array_merge($results, iterator_to_array($mongo->feedfollows->find(array('uid' => (int)$uid)), false));
                if(count($results) > 0)
                    return $results;
                return array();
            }

        public function _setLastRead($uid, $followUid)
            {
                if(!$uid || !$followUid)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $results = $mongo->feedfollows->update(array('uid' => (int)$uid, 'followUid' => (int)$followUid), array('$set' => array('lastRead' => new MongoDate())));
                if($results['ok'] == 1)
                    return true;
                return false;
            }

        public function _setLastReads($uid, $followUids)
            {
                if(!$uid || !is_array(followUids))
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $results = $mongo->feedfollows->update(array('uids' => array('$in' => $followUids)), array('$set' => array('lastRead' => new MongoDate())));
                if($results['ok'] == 1)
                    return true;
                return false;
            }
    }