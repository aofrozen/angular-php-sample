<?php

namespace app\controllers;

use \Phalcon\Mvc\View,
    app\models\profiles;

class NotificationController extends ControllerBase
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
                $this->view->setVar('title', 'Notifications');
                $this->view->setVar('ngView', $ngView);
                $this->view->pick('notification/notification');
            }
    }