<?php

namespace app\controllers;

use \Phalcon\Mvc\View,
        app\models\profiles;

class FeedController extends ControllerBase
    {
        public function timelineAction()
            {
                $pid = $this->dispatcher->getParam('uid');
                if(!is_numeric($pid))
                    $pid = (new profiles())->convertUsernameToPID($pid)['uid'];
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/timeline/timeline');
            }
        public function feedItemModalAction()
            {
                $id = $this->dispatcher->getParam('id');
                $ngView = $this->request->get('ng-view');
                if ($ngView == 'false')
                    $this->view->setRenderLevel(View::LEVEL_LAYOUT);

                /* Feed Comment */
                $this->view->commentSenderName = 'You';
                $this->view->commentSenderUsername = 'you';
                $this->view->ngView = $ngView;
                $this->view->pick('feed/feedItemModal');
            }
        public function feedItemAction()
            {
                $id = $this->dispatcher->getParam('id');
                $ngView = $this->request->get('ng-view');
                if ($ngView == 'false')
                    $this->view->setRenderLevel(View::LEVEL_LAYOUT);

                /* Feed Comment */
                $this->view->commentSenderName = 'You';
                $this->view->commentSenderUsername = 'you';
                $this->view->ngView = $ngView;
                $this->view->pick('feed/feedItem');
            }
        public function feedItemPopoverAction()
            {
                $this->view->setRenderLevel(VIEW::LEVEL_LAYOUT);
                $this->view->pick('feed/feedItemPopover');
            }
        public function feedItemPrivacyModalAction()
        {
            $this->view->setRenderLevel(VIEW::LEVEL_LAYOUT);
            $this->view->pick('feed/feedItemPrivacyModal');
        }
    }