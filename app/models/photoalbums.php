<?php

namespace app\models;

use MongoDate,
    MongoId;

class photoalbums extends \Phalcon\Mvc\Collection
    {
        public $ts;
        public $uid;
        public $albumName;
        public $albumAvatar;
        public $albumPrivacy; //maybe fix this for better performance

        public function getSource()
            {
                return 'photoalbums';
            }

        public function _create($uid, $albumAvatarData = null, $caption = null, $privacy = null)
            {
                if(!$uid)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $photoalbum = new \stdClass();
                $photoalbum->uid = $uid;
                $photoalbum->ts = new MongoDate();
                $photoalbum->albumAvatar = $albumAvatarData;
                $photoalbum->caption = $caption;
                $photoalbum->privacy = $privacy;
                if($mongo->photoalbums->insert($photoalbum)['ok'] == 1)
                    return $photoalbum;
                return false;
            }

        public function _getAlbums($uid)
            {
                if(!$uid)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $results = iterator_to_array($mongo->photoalbums->find(array('uid' => (int)$uid)), false);
                if(count($results) > 0)
                    return $results;
                return array();
            }

        public function _setAvatar($uid, $id, $albumAvatar)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photoalbums->update(array('uid' => $uid, '_id' => new MongoDate($id), array('albumAvatar' => $albumAvatar))))
                    return true;
                return false;
            }

        public function _setPrivacy($uid, $id, $privacy)
            {
                if(!$uid || $id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photoalbums->update(array('uid' => $uid, '_id' => new MongoDate($id)), array('privacy' => array('$set' => $privacy))))
                    return true;
                return false;
            }

        public function _setCaption($uid, $id, $caption)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photoalbums->update(array('uid' => $uid, '_id' => new MongoDate($id)), array('caption' => array('$set' => $caption))))
                    return true;
                return false;
            }

        public function __deleteAlbums($uid) //only administrator & bot handle this for deleting users.
            {
                if(!$uid)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photoalbums->remove(array('uid' => $uid)))
                    return true;
                return false;
            }

        public function _delete($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photoAlbums->remove(array('uid' => $uid, '_id' => new MongoId($id))))
                    return true;
                return false;
            }
    }