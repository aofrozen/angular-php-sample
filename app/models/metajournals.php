<?php

namespace app\models;

use MongoDate;

class metajournals extends \Phalcon\Mvc\Collection
    {
        public $uid;
        public $ts;
        public $stats;
        public $limit;

        public function getSource()
            {
                return 'metajournals';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function build($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $metajournals = new \stdClass();
                $metajournals->ts = new MongoDate();
                $metajournals->uid = (int)$uid;
                $metajournals->stats->total->journalCount = (int)0;
                $metajournals->stats->day->ts = new MongoDate();
                $metajournals->stats->day->journalCount = (int)0;
                $metajournals->limit->PPD = (int)100; //Post per day limit
                $metajournals->limit->JournalCountMax = (int)10000; //Journal count max
                if ($mongo->metajournals->insert($metajournals)['ok'] == 1)
                    return $metajournals;
                return false;
            }
    }