<?php

namespace app\controllers;

use \Phalcon\Mvc\View,
        app\models\profiles;

class FriendController extends ControllerBase
    {
        public function fireAction()
            {
                $pid = $this->dispatcher->getParam('uid');
                if(!is_numeric($pid))
                    $pid = (new profiles())->convertUsernameToPID($pid)['uid'];
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/friends/friends');
            }

        public function friendStatusListAction()
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                echo "Test";

            }
    }