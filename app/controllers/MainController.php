<?php

namespace app\controllers;
use \Phalcon\Mvc\View;
class socialSampleController extends ControllerBase
    {
        public function initialize()
            {
                parent::initialize();
            }

        public function homeAction()
            {
                $this->tag->setTitle('socialSample');
            }

        public function notFoundAction()
            {
                $ngView = $this->request->get('ng-view');
                if ($ngView == 'false')
                    $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->ngView = $ngView;
                $this->tag->setTitle('404');
                $this->view->pick('error/404');
            }
    }