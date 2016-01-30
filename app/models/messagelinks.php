<?php


namespace app\models;

use MongoDate,
    MongoId;

class messagelinks extends \Phalcon\Mvc\Collection
{
    public $ts;
    public $uid;
    public $roomId;

    public function getSource()
    {
        return 'messagelinks';
    }

    public function _create($uid, $roomId)
    {
        if(!$uid || !$roomId)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $messagelink = new \stdClass();
        $messagelink->ts = new MongoDate();
        $messagelink->uid = $uid;
        $messagelink->roomId = $roomId;
        if($mongo->messagelinks->insert($messagelink))
            return true;
        return false;
    }

    public function _get($uid)
    {
        if(!$uid)
            return array();
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->messagelinks->find(array('uid' => $uid)), false);
        if(count($results) > 0)
            return $results;
        return array();
    }

    public function _delete($uid, $id)
    {
        if(!$uid || !$id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->messagelinks->remove(array('uid' => $uid, '_id' => new MongoId($id))))
            return true;
        return false;
    }
}