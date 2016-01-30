<?php

namespace app\models;

use MongoDate;

class metaphotos extends \Phalcon\Mvc\Collection
    {
        public $ts;
        public $uid;
    /*
     * Metaphoto is used for locating photo collection for user, statistics of photos
     */

    /*
     * This is for locating photo storage
     */
        public function getSource()
            {
                return 'metaphotos';
            }

        public function build($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $metaphotos = new \stdClass();
                $metaphotos->ts = new MongoDate();
                $metaphotos->uid = $uid;
                $metaphotos->data = '';
                if ($mongo->metaphotos->insert($metaphotos)['ok'] == 1)
                    return $metaphotos;
                return false;
            }
    }