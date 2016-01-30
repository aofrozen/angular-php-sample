<?php

namespace app\controllers;

use \Phalcon\Mvc\View,
    app\models\profiles,
    app\models\users,
    app\models\privacy,
    app\library\session\userSessions;

class SettingsController extends ControllerBase
    {
        public function fireAction()
            {
                $uid = (new userSessions())->requiredLogin(false);
                $ngView = $this->request->get('ng-view');
                if ($ngView == 'false')
                    $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $profileData = (new profiles())->_get($uid);
                $userData = (new users())->_get($uid);
                $privacyData = (new privacy())->_get($uid);
                $this->view->title = 'Settings';
                $this->view->ngView = $ngView;
                $this->view->pick('settings/settings');
            }

        public function menuAction()
            {
                $uid = (new userSessions())->requiredLogin(false);
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('settings/settingsMenu');
            }
    }