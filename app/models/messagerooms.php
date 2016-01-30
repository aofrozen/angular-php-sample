<?php

namespace app\models;

use MongoDate,
    MongoId;

class messagechats extends \Phalcon\Mvc\Collection
{
    public $ts;
    public $contacts;

    public function getSource()
    {
        return 'messagerooms';
    }

    public function _create($contacts)
    {
        $mongo = $this->getDI()->getShared('mongo');
        $messageroom = new \stdClass();
        $messageroom->ts = new MongoDate();
        $messageroom->contacts = $contacts;
        if($mongo->messagerooms->insert($messageroom)['ok'] == 1)
            return true;
        return false;
    }

    public function _delete($id)
    {
        if(!$id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->messagerooms->remove(array('_id' => new MongoId($id))))
            return true;
        return false;
    }

    public function _setContacts($id, $contacts)
    {
        if(!$id)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->messagerooms->update(array('_id' => new MongoId($id)), array('contacts' => array('$set' => $contacts))))
            return true;
        return false;
    }
}