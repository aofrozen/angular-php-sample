<?php

namespace app\controllers;

use \Phalcon\Mvc\View;

class MessageController extends ControllerBase
    {
        public function initialize()
            {
                parent::initialize();
            }

        public function fireAction()
            {
                $ngView = $this->request->get('ng-view');
                if ($ngView == 'false')
                    $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->setVar('title', 'Message');
                $this->view->setVar('ngView', $ngView);
                $this->view->pick('message/message');
            }
    }