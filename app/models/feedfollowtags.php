<?php

namespace app\models;

use MongoDate,
    MongoId,
    \Phalcon\Mvc\Collection;

class feedfollowtags extends collection
{
    public $ts;
    public $uid;
    public $followId;
    public $tagName;

    public function getSource()
    {
        return 'feedfollowtags';
    }
    public function _create($uid, $followId, $tagName)
    {
        if(!$uid || !$followId || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('feedfollowtags');
        $feedFollowTag = new \stdClass();
        $feedFollowTag->ts = new MongoDate();
        $feedFollowTag->uid = $uid;
        $feedFollowTag->followId = $followId;
        $feedFollowTag->tagName = $tagName;
        if($mongo->feedfllowtags->insert($feedFollowTag)['ok'] == 1)
            return $feedFollowTag;
        return false;

    }
    public function _delete($uid, $id)
    {
        if(!$uid || $id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->feedfollowtags->remove(array('uid' => $uid, '_id' => new MongoId($id))))
            return true;
        return false;
    }
    public function _get($uid)
    {
        if(!$uid)
            return array();
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->feedfollowtags->find(array('uid' => $uid)), false);
        if(count($results) > 0)
            return $results;
        return array();
    }

}