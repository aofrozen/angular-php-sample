<?php

namespace app\models;

use MongoDate;

class metaprofiles extends \Phalcon\Mvc\Collection
    {
        public $ts;
        public $uid;
        public $data;
        public $stats;
        public $lastUpdate;

    /*
     *
     */
        public function getSource()
            {
                return 'metaprofiles';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function build($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $metaprofile = new \stdClass();
                $metaprofile->stats = new \stdClass();
                $metaprofile->ts = new MongoDate();
                $metaprofile->uid = $uid;
                $metaprofile->stats->updateCount = 0;
                $metaprofile->lastUpdate = new MongoDate();
                if ($mongo->metaprofiles->insert($metaprofile)['ok'] == 1)
                    return true;
                return false;
            }
    }