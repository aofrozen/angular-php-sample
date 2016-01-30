<?php


namespace app\models;

use MongoId,
    MongoDate,
    \Phalcon\Mvc\Collection;

class friendtaggroups extends collection
{
    public $ts;
    public $uid;
    public $tagName;

    public function getSource()
    {
        return 'friendTagGroups';
    }

    public function _create($uid, $tagName)
    {
        if(!$uid || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $friendTagGroup = new \stdClass();
        $friendTagGroup->ts = new MongoDate();
        $friendTagGroup->uid = $uid;
        $friendTagGroup->tagName = $tagName;
        if($mongo->friendTagGroups->insert($friendTagGroup)['ok'] == 1)
            return $friendTagGroup;
        return false;
    }

    public function _delete($uid, $id)
    {
        if(!$uid || !$id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->friendTagGroups->remove(array('uid' => $uid, '_id' => new MongoId($id))))
            return true;
        return false;
    }

    public function _get($uid)
    {
        if(!$uid)
            return array();
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->friendTagGroups->find(array('uid' => $uid)), false);
        if(count($results) > 0)
            return $results;
        return array();
    }

    public function _exists($uid, $tagName)
    {
        if(!$uid || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->friendTagGroups->find(array('uid' => $uid, 'tagName' => $tagName)), false);
        if(count($results) > 0)
            return true;
        return false;

    }
}