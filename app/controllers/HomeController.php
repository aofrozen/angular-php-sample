<?php

namespace app\controllers;

use \Phalcon\Mvc\View,
    app\library\session\userSessions,
    app\models\profiles;

class HomeController extends ControllerBase
    {
        public function initialize()
            {
                parent::initialize();
            }

        public function fireAction()
            {
                $uid = (new userSessions())->requiredLogin(false);
                $ngView = $this->request->get('ng-view');
                if ($ngView == 'false')
                    $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $profileData = (new profiles())->_get($uid);
                $this->view->pid = $uid;
                $this->view->title = 'Home';
                if($profileData)
                    {
                        $this->view->homeUsername = $profileData['username'];
                        $this->view->homeName = $profileData['fName'] . ' ' . $profileData['lName'];
                        $this->view->ngView = $ngView;
                    }

                $this->view->pick('home/home');
            }
    }