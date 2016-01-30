<?php

namespace app\controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
    {
        public function initialize()
            {
                $this->tag->appendTitle(' - socialSample');
            }
    }