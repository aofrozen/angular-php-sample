<?php

namespace app\models;

use MongoDate,
    MongoId,
    \Phalcon\Mvc\Collection;

class feedtags extends collection
{
    public $ts;
    public $uid;
    public $feedId;
    public $tagName;
    public function getSource()
    {
        return 'feedtags';
    }
    public function _create($uid, $feedId, $tagName)
    {
        if(!$uid || !$feedId || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $feedTag = new \stdClass();
        $feedTag->ts = new MongoDate();
        $feedTag->uid = $uid;
        $feedTag->feedId = $feedId;
        $feedTag->feedName = $tagName;
        if($mongo->feedtags->insert($feedTag)['ok'] == 1)
            return $feedTag;
        return false;
    }
    public function _delete($uid, $id)
    {
        if(!$uid || !$id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->feedtags->remove(array('uid' => $uid, '_id' => new MongoId($id))))
            return true;
        return false;
    }
    public function _get($uid, $feedId)
    {
        if(!$uid || !$feedId)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->feedtags->find(array('uid' => $uid, 'feedId' => $feedId)), false);
        if(count($results))
            return $results;
        return array();
    }
}