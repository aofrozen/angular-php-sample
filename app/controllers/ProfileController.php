<?php

namespace app\controllers;

use \Phalcon\Mvc\View,
    \Phalcon\Mvc\View\Simple as SimpleView,
    app\models\users,
    app\models\profiles,
    app\controllers\friendController;

class ProfileController extends ControllerBase
    {
        public function initialize()
            {
                parent::initialize();
            }

        public function fireAction($page)
            {
                $pid = $this->dispatcher->getParam('uid');
                if(!is_numeric($pid))
                $pid = (new profiles())->convertUsernameToPID($pid)['uid'];
                $ngView = $this->request->get('ng-view');
                if ($ngView == 'false')
                    $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pid = $pid;
                $this->view->ngView = $ngView;
                $this->view->friendStatusList = $this->view->getPartial('friend/statusList');
                /* Profile Details */
                $this->view->title = 'Profile';
                $this->view->pick('profile/profile');
            }

        public function aboutAction()
            {
                $pid = $this->dispatcher->getParam('uid');
                if(!is_numeric($pid))
                    $pid = (new profiles())->convertUsernameToPID($pid)['uid'];
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);

                $profileData = (new profiles())->_get($pid);
                $userData = (new users())->_get($pid);

                /* About Details */
                $this->view->profileGender = $profileData['gender'];
                $this->view->profileBirthday = false;
                $this->view->profileLocation = false; //
                $this->view->profileMobileNumbers = $profileData['mobileNumbers'];
                $this->view->profileEmail = $userData['email'];

                $this->view->pick('profile/about/about');
            }
    }