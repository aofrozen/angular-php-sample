<?php

namespace app\models;

use \Phalcon\Mvc\Collection,
    MongoDate;

class FSDelete extends collection
    {
        public $ts;

        public function getSource()
            {
                return 'FSDelete';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function prepare($data)
            {
                if(!$data)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $FSDelete = new \stdClass();
                $FSDelete->ts = new MongoDate();
                $FSDelete->data = $data;
                if($mongo->fsdelete->insert($FSDelete)['ok'] == 1)
                    return true;
                return false;
            }
    }