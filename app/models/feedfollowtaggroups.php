<?php


namespace app\models;

use MongoId,
    MongoDate,
    \Phalcon\Mvc\Collection;

class feedfollowtaggroups extends collection
{
    public function getSource()
    {
        return 'feedfollowtaggroups';
    }

    public function _create($uid, $tagName)
    {
        if(!$uid || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $feedfollowtaggroup = new \stdClass();
        $feedfollowtaggroup->ts = new MongoDate();
        $feedfollowtaggroup->uid = $uid;
        $feedfollowtaggroup->tagName = $tagName;
        if($mongo->feedfollowtaggroups->insert($feedfollowtaggroup)['ok'] == 1)
            return $feedfollowtaggroup;
        return false;
    }

    public function _delete($uid, $id)
    {
        if(!$uid || $id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->feedfollowtaggroups->remove(array('uid' => $uid, '_id' => new MongoId($id))))
            return true;
        return false;
    }

    public function _get($uid)
    {
        if(!$uid)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $results = $mongo->feedfollowtaggroups->find(array('uid' => $uid));
        if(count($results) > 0)
            return $results;
        return array();
    }

    public function _exists($uid, $tagName)
    {
        if(!$uid || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->feedfollowtaggroups->find(array('uid' => $uid, 'tagName' => $tagName)), false);
        if(count($results) > 0)
            return true;
        return false;
    }
}