<?php

namespace app\models;

use \Phalcon\Mvc\Collection,
    MongoDate;

class FSStat extends collection
    {
        public $ts;
        public $host;
        public $dirLevelCount_A;
        public $dirLevelCount_B;
        public $fileCount;

        public function getSource()
            {
                return 'fsstat';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function add($host)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $fs = new \stdClass();
                $fs->ts = new MongoDate();
                $fs->host = $host;
                $fs->dirLevelCount_A = 1;
                $fs->dirLevelCount_B = 1;
                $fs->fileCount = 1;
                $mongo->fsstat->insert($fs);
            }

        public function update($host, $dirLevelIncrement_A, $dirLevelIncrement_B, $fileIncrement)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->fsstat->update(array('host' => $host), array('$inc' => array('dirLevelCount_A' => $dirLevelIncrement_A, 'dirLevelCount_B' => $dirLevelIncrement_B, 'fileCount' => $fileIncrement)));
            }
    }