<?php

namespace app\models;

use MongoDate,
    MongoId,
    \Phalcon\Mvc\Collection;

class friendtags extends collection
{
    public $ts;
    public $uid;
    public $friendId;
    public $tagName;

    public function getSource()
    {
        return 'friendtags';
    }
    public function _create($uid, $friendId, $tagName)
    {
        if(!$uid || !$friendId || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $friendTag = new \stdClass();
        $friendTag->ts = new MongoDate();
        $friendTag->uid = $uid;
        $friendTag->friendId = $friendId;
        $friendTag->tagName = $tagName;
        if($mongo->friendTags->insert($friendTag)['ok'] == 1)
            return $friendTag;
        return false;
    }
    public function _delete($uid, $id)
    {
        if(!$uid || !$id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->friendTags->remove(array('uid' => $uid, '_id' => new MongoId($id))))
            return true;
        return false;
    }
    public function _get($uid)
    {
        if(!$uid)
            return array();
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->friendTags->find(array('uid' => $uid)), false);
        if(count($results) > 0)
            return $results;
        return array();
    }
}