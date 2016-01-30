<?php

namespace app\library\fs;

use app\models\fsbalance,
    app\models\fslevelinc,
    app\models\fsstat,
    app\models\fsdelete,
    Phalcon\Di\Injectable;

class fileStorage extends Injectable
    {
        public function upload($source = null, $collectionLoc = null, $FSData = false, $unlink = true, $customFilename = false)
            {
                /*
                 * fsbalance -
                 */
                if ($FSData == false)
                    {
                        $FSLocationData = (new FSBalance())->getAvailableFileLocation();
                        if (count($FSLocationData) < 1)
                            {
                                $this->install();
                                $FSLocationData = (new FSBalance())->getAvailableFileLocation();
                            }
                        if($customFilename)
                            $FSLocationData->fileID = $customFilename; //this can be dangerous if not use it properly
                        $this->makeDirExists($FSLocationData);
                        copy($source, $this->getFilesDir() . $FSLocationData->dirLevel_A . '/' . $FSLocationData->dirLevel_B . '/' . $FSLocationData->fileID . '.' . pathinfo($source, PATHINFO_EXTENSION)); //basename($source)
                        $FSLocationData->fileLocation = $this->getFilesDir() . $FSLocationData->dirLevel_A . '/' . $FSLocationData->dirLevel_B . '/' . $FSLocationData->fileID . '.' . pathinfo($source, PATHINFO_EXTENSION);
                        $FSLocationData->webFileLocation = $this->getWebFilesDir($FSLocationData->host) . $FSLocationData->dirLevel_A . '/' . $FSLocationData->dirLevel_B . '/' . $FSLocationData->fileID . '.' . pathinfo($source, PATHINFO_EXTENSION);
                        if($unlink) unlink($source);
                    } elseif ($FSData->fileLocation)
                    {
                        copy($source, $FSData->fileLocation);
                        if($unlink) unlink($source);
                        return $FSData;
                    }

                /*
                 * FSLocationData
                 *
                 * host
                 * dirLevel_A
                 * dirLevel_B
                 * fileCount
                 * fileID
                 */

                return $FSLocationData;
            }

        public function prepareDelete($data)
            {
                return (new FSDelete())->prepare($data);
            }

        public function delete($host, $dirLevel_A, $dirLevel_B)
            {
                (new FSBalance())->deleteFileLocation($host, $dirLevel_A, $dirLevel_B);
            }

        private function removeAll()
            {
                $this->mongo->fslevelinc->remove();
                $this->mongo->fsbalance->remove();
                $this->mongo->fsstat->remove();
            }

        public function install()
            {
                $mongo = $this->getDI()->getShared('mongo');
                //create fslevelinc
                $fslevelinc = new \stdClass();
                $fslevelinc->host = '192.168.56.102';
                $fslevelinc->dirLevel_A = 0;
                $fslevelinc->dirLevel_B = 0;
                $mongo->fslevelinc->insert($fslevelinc);

                //create fsbalance
                $fsbalance = new \stdClass();
                $fsbalance->host = '192.168.56.102';
                $fsbalance->dirLevel_A = 0;
                $fsbalance->dirLevel_B = 0;
                $fsbalance->fileCount = 0;
                $fsbalance->fileID = 0;
                $mongo->fsbalance->insert($fsbalance);

                //create fsstat
                $fsstat = new \stdClass();
                $fsstat->host = '192.168.56.102';
                $fsstat->dirLevelCount_A = 1;
                $fsstat->dirLevelCount_B = 1;
                $fsstat->fileCount = 0;
                $mongo->fsstat->insert($fsstat);
            }

        private function makeDirExists($FSLocationData)
            {
                $dirLevel_A = $this->getFilesDir() . $FSLocationData->dirLevel_A;
                $dirLevel_B = $this->getFilesDir() . $FSLocationData->dirLevel_A . '/' . $FSLocationData->dirLevel_B;
                if (!file_exists($dirLevel_A))
                    mkdir($dirLevel_A);
                if (!file_exists($dirLevel_B))
                    mkdir($dirLevel_B);
            }

        private function getWebFilesDir($host)
            {
                return $host . '/files/';
            }

        private function getFilesDir()
            {
                return dirname(dirname(dirname(__DIR__))) . '/public/files/';
            }
    }