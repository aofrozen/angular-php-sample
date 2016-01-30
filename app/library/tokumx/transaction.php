<?php

namespace app\library\tokumx;

use \Phalcon\DI\Injectable;
use Phalcon\Exception;

class transaction extends Injectable
    {
        public function beginTransaction()
            {
                $result = $this->mongo->execute('db.beginTransaction()');
                if ($result['ok'] != 1)
                    {
                        throw new Exception($result['status']);
                    }
            }

        public function commitTransaction()
            {
                $result = $this->mongo->execute('db.commitTransaction()');
                if ($result['ok'] != 1)
                    {
                        throw new Exception($result['status']);
                    }
            }

        public function rollbackTransaction()
            {
                $result = $this->mongo->execute('db.rollbackTransaction()');
                if ($result['ok'] != 1)
                    {
                        throw new Exception($result['status']);
                    }
            }
    }