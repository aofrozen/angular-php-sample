<?php

namespace app\models;

use MongoDate;

class sessions extends \Phalcon\Mvc\Collection
    {
        public $ts;
        public $uid;
        public $key;
        public $ipAddress;

        public function getSource()
            {
                return 'sessions';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }
    }