<?php

namespace app\models;

use MongoDate,
    MongoId,
    \Phalcon\Mvc\Collection;

class phototags extends collection
{
    public $ts;
    public $uid;
    public $tagName;
    public $photoId;

    public function getSource()
    {
        return 'phototags';
    }
    public function _create($uid, $photoId, $tagName)
    {
        $mongo = $this->getDI()->getShared('mongo');
        $photoTag = new \stdClass();
        $photoTag->ts = new MongoDate();
        $photoTag->uid = $uid;
        $photoTag->photoId = $photoId;
        $photoTag->tagName = $tagName;
        if($mongo->phototags->insert(array('ts' => new mongoDate(), 'uid' => $uid, 'photoId' => $photoId, 'tagName' => $tagName))['ok'] == 1)
            return $photoTag;
        return false;
    }
    public function _delete($uid, $id)
    {
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->phototags->remove(array('uid' => $uid, '_id' => new mongoId($id))))
            return true;
        return false;
    }
    public function _get($uid, $tagName)
    {
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->phototags->find(array('uid' => $uid, 'tagName' => $tagName)), false);
        if(count($results))
            return $results;
        return array();
    }
}