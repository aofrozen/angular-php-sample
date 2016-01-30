<?php


namespace app\models;

use \Phalcon\Mvc\Collection,
    MongoDate,
    app\models\fsstat,
    app\models\fslevelinc;

class FSBalance extends collection
    {
        public $ts;
        public $host;
        public $dirLevel_A;
        public $dirLevel_B;
        public $fileCount;
        public $fileID;

        public function getSource()
            {
                return 'fsbalance';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function getAvailableFileLocation()
            {
                $mongo = $this->getDI()->getShared('mongo');
                $this->updateNextAvailable();
                return json_decode(json_encode(iterator_to_array($mongo->fsbalance->find(array(), array('ts' => 0, '_id' => 0, 'fileCount' => 0))->sort(array('fileCount' => 1))->limit(1), false)[0]));
            }

        public function deleteFileLocation($host, $dirLevel_A_loc, $dirLevel_B_loc)
            {
                $mongo = $this->getDI()->getShared('mongo');
                (new FSStat())->update($host, 0, 0, -1);
                $mongo->fsbalance->update(array('host' => $host, 'dirLevel_A' => $dirLevel_A_loc, 'dirLevel_B' => $dirLevel_B_loc), array('$inc' => array('fileID' => -1, 'fileCount' => -1)));
            }

        private function add($host, $dirLevel_A_loc, $dirLevel_B_loc)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $fs = new \stdClass();
                $fs->ts = new MongoDate();
                $fs->host = $host;
                $fs->dirLevel_A = $dirLevel_A_loc;
                $fs->dirLevel_B = $dirLevel_B_loc;
                $fs->fileCount = 0;
                $fs->fileID = 0;
                $mongo->fsbalance->insert($fs);
            }

        private function updateNextFile($host, $dirLevel_A_loc, $dirLevel_B_loc)
            {
                $mongo = $this->getDI()->getShared('mongo');
                (new FSStat())->update($host, 0, 0, 1);
                $mongo->fsbalance->update(array('host' => $host, 'dirLevel_A' => $dirLevel_A_loc, 'dirLevel_B' => $dirLevel_B_loc), array('$inc' => array('fileID' => 1, 'fileCount' => 1)));
            }

        public function updateNextAvailable()
            {
                $nextFileMax = $nextDirLevelBMax = false;
                $mongo = $this->getDI()->getShared('mongo');
                $fsData = json_decode(json_encode(iterator_to_array($mongo->fsbalance->find()->sort(array('fileCount' => 1))->limit(1), false)[0]));
                //update fsbalance
                if ($fsData->fileCount >= 1000)
                    $nextFileMax = true;

                //if next file doesn't hit max limit then update next file
                if ($nextFileMax === false)
                    {
                        $dirLevel_B_loc = $fsData->dirLevel_B;
                        $dirLevel_A_loc = $fsData->dirLevel_A;
                        $this->updateNextFile($fsData->host, $dirLevel_A_loc, $dirLevel_B_loc);
                    }

                //if next file hits max limit then next dir level B
                if ($nextFileMax === true)
                    {
                        $FSLevelInc = FSLevelInc::findFirst(array(array('host' => 'localhost')));

                        //create new level a (no limit)
                        if ($FSLevelInc->dirLevel_A >= 10000)
                            {
                                die("Error: This dir level is reached to the max level limit");
                            }

                        $FSLevel = (new FSLevelInc())->updateNextLevel($fsData->host);
                        $this->add($fsData->host, $FSLevel->dirLevel_A, $FSLevel->dirLevel_B);
                    }
            }
    }