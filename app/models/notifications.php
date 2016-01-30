<?php

namespace app\models;

use MongoId,
    MongoDate;

class notifications extends \Phalcon\Mvc\Collection
    {

        public $ts;
        public $uid;
        public $fid;
        public $type;
        public $read;
        public $data;
    /*
     * Type: friend request, feed post comment, photo comment, profile comment
     *
     * friend request data: id: from_uid
     * feed post comment: from_uid, feed_post_id, feed_post_comment_id
     * photo comment: from_uid, photo_id, photo_comment_id
     * profile comment: from_uid, profile_comment_id
     *
     */
    /*
     * data.id + type
     */

        public function getSource()
            {
                return 'notifications';
            }

        public function _get($uid)
            {
                if(!$uid)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $result = iterator_to_array($mongo->notifications->find(array('uid' => (int)$uid)), false);
                if(count($result))
                    return $result;
                return array();
            }

        public function _getItem($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                return notifications::findFirst(array(array('uid' => (int)$uid, '_id' => new MongoId($id))));
            }

        public function _delete($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                $notification = notifications::findFirst(array(array('uid' => (int)$uid, '_id' => new MongoId($id))));
                if (!$notification)
                    return true;
                if ($notification->delete())
                    {
                        return true;
                    } else
                    {
                        return false;
                    }
            }

        public function _create($uid, $type, $fid, $data)
            {
                if(!$uid || !$type || !$fid)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $notification = new \stdClass();
                $notification->ts = new MongoDate();
                $notification->uid = (int)$uid;
                $notification->fid = (int)$fid;
                $notification->read = (boolean)false;
                $notification->type = (string)$type;
                $notification->data = $data;
                if ($mongo->notifications->insert($notification)['ok'] == 1)
                    return $notification;
                return false;
            }

        public function _exist($uid, $type, $fid)
            {
                if(!$uid || !$type || !$fid)
                    return false;
                $notification = notifications::findFirst(array(array('uid' => (int)$uid, 'type' => (string)$type, 'fid' => (int)$fid)));
                if ($notification)
                    return $notification;
                return false;
            }

        public function _read($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                $notification = notifications::findFirst(array(array('uid' => (int)$uid, '_id' => new MongoId($id))));
                if (!$notification)
                    return false;
                $notification->read = true;
                if ($notification->save())
                    return true;
                return false;
            }

        public function _unread($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                $notification = notifications::findFirst(array(array('uid' => (int)$uid, '_id' => new MongoId($id))));
                if (!$notification)
                    return false;
                $notification->read = false;
                if ($notification->save())
                    return true;
                return false;
            }

    }