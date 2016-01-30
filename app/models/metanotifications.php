<?php

namespace app\models;

use MongoDate;
use Phalcon\Mvc\Model\Validator\Numericality;

class metanotifications extends \Phalcon\Mvc\Collection
    {

        public $uid;
        public $ts;
        public $stats;

        public function getSource()
            {
                return 'metanotifications';
            }

        public function build($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $metanotification = new \stdClass();
                $metanotification->stats = new \stdClass();
                $metanotification->stats->all = new \stdClass();
                $metanotification->stats->new = new \stdClass();
                $metanotification->uid = $uid;
                $metanotification->ts = new MongoDate();
                $metanotification->stats->all->notificationCount = 0;
                $metanotification->stats->new->notificationCount = 0;
                if ($mongo->metanotifications->insert($metanotification)['ok'] == 1)
                    return $metanotification;
                return false;
            }

        public function updateNotificationCount($uid, $count)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->metanotifications->update(array('uid' => $uid), array('$inc' => array('stats.all.notificationCount' => (int)$count)));
            }

        public function updateNewNotificationCount($uid, $count)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->metanotifications->update(array('uid' => $uid), array('$inc' => array('stats.new.notificationCount' => (int)$count)));
            }

        public function resetNewNotificationCount($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->metanotifications->update(array('uid' => $uid), array('stats.new.notificationCount' => (int)0));
            }
    }