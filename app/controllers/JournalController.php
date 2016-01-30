<?php

namespace app\controllers;
class JournalController extends ControllerBase
    {
        public function initialize()
            {
                parent::initialize();
            }

        public function fireAction()
            {
                $this->tag->prependTitle('Journal');
            }
    }