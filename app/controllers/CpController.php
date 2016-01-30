<?php

namespace app\controllers;

use \Phalcon\Mvc\View,
    app\library\session\userSessions;

class CpController extends ControllerBase
    {
        public function fireAction()
            {
                $uid = (new userSessions())->requiredLogin(false);
                $ngView = $this->request->get('ng-view');
                if ($ngView == 'false')
                    $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('cp/cp');
                $this->view->pid = $uid;
                $this->view->setVar('title', 'Control Panel');
                $this->view->setVar('ngView', $ngView);
            }
    }