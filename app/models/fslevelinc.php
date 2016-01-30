<?php


namespace app\models;

use \Phalcon\Mvc\Collection,
    app\models\fsstat;

class FSLevelInc extends collection
    {
        public $host;
        public $dirLevel_A;
        public $dirLevel_B;

        public function getSource()
            {
                return 'fslevelinc';
            }

        public function add($host)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $fs = new \stdClass();
                $fs->host = $host;
                $fs->dirLevel_A = 0;
                $fs->dirLevel_B = 0;
                $mongo->fslevelinc->insert($fs);
            }

        public function updateNextLevel($host)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $fs = json_decode(json_encode(iterator_to_array($mongo->fslevelinc->find(array('host' => $host)), false)[0]));
                if ($fs->dirLevel_B >= 1000)
                    {
                        (new FSStat())->update($host, 1, 1, 0);
                        $mongo->fslevelinc->update(array('host' => $host), array('$inc' => array('dirLevel_A' => 1), '$set' => array('dirLevel_B' => 0)));
                    } else
                    {
                        (new FSStat())->update($host, 0, 1, 0);
                        $mongo->fslevelinc->update(array('host' => $host), array('$inc' => array('dirLevel_B' => 1)));
                    }
                return json_decode(json_encode(iterator_to_array($mongo->fslevelinc->find(array('host' => $host)), false)[0]));
            }

        public function getCurrentLevel($host)
            {
                $mongo = $this->getDI()->getShared('mongo');
                return iterator_to_array($mongo->fslevelinc->find(array('host' => $host)));
            }
    }