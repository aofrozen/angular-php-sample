<?php


namespace app\models;

use MongoDate,
    MongoId;

class messagechats extends \Phalcon\Mvc\Collection
{
    public $ts;
    public $roomId;
    public $uid;
    public $message;
    public $media;

    public function getSource()
    {
        return 'messagechats';
    }

    public function _create($uid, $roomId, $message)
    {
        if(!$uid || !$roomId)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $messagechat = new \stdClass();
        $messagechat->ts = new MongoDate();
        $messagechat->uid = $uid;
        $messagechat->roomId = $roomId;
        $messagechat->message = $message;
        if($mongo->messagechats->insert($messagechat)['ok'] == 1)
            return true;
        return false;
    }

    public function _get($uid, $roomId, $limit = 15, $ts = null)
    {
        if(!$uid || !$roomId)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->messagechats->find(array('uid' => $uid, 'roomId' => $roomId))->limit($limit), false);
        if(count($results) > 0 )
            return $results;
        return false;
    }

    public function _delete($uid, $roomId, $id)
    {
        if(!$uid || !$roomId || !$id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->messagechats->remove(array('uid' => $uid, 'roomId' => $roomId, '_id' => new MongoId($id))))
            return true;
        return false;
    }
}