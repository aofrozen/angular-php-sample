<?php

namespace app\models;

use MongoDate,
    MongoId;

/*
 * Privacy
 * show - only me, friends or anyone
 *
 */

class photos extends \Phalcon\Mvc\Collection
    {
        public $ts;
        public $uid;
        public $photo;
        public $caption;
        public $privacy;
        public $albumId;
        public $tags;

        public function getSource()
            {
                return 'photos';
            }

        public function _create($uid, $albumId, $photoData, $caption, $privacy, $tags)
            {
                if(!$uid || !$photoData)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $photo = new \stdClass();
                $photo->ts = new MongoDate();
                $photo->uid = (int)$uid;
                $photo->photo = $photoData;
                $photo->albumId = (string)$albumId;
                $photo->caption = (string)$caption;
                $photo->privacy = $privacy;
                $photo->tags = $tags;

                if($mongo->photos->insert($photo)['ok'] == 1)
                    return $photo;
                return false;
            }

        public function _deletePhoto($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photos->remove(array('uid' => (int)$uid, '_id' => new MongoId($id))))
                    return true;
                return false;
            }

        public function _setPhoto($uid, $id, $caption, $privacy, $tags)
            {
                if(!$uid || !$id)
                        return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photos->update(array('uid' => (int)$uid, '_id' => new MongoId($id)), array('$set' => array('caption' => $caption, 'privacy' => $privacy, 'tags' => $tags)))['ok'] == 1)
                    return true;
                return false;
            }

        public function _setPrivacy($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photos->update(array('uid' => (int)$uid, '_id' => new MongoId($id)), array('privacy' => array('$set' => $privacy))))
                    return true;
                return false;
            }

        public function _setCaption($uid, $id, $caption = null)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photos->update(array('uid' => (int)$uid, '_id' => $id), array('caption' => array('$set' => $caption))))
                    return true;
                return false;
            }

        public function _getAll($uid)
            {
                if(!$uid)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $results = iterator_to_array($mongo->photos->find(array('uid' => (int)$uid), array('uid' => 1, 'photo.webFileLocation' => 1, 'caption' => 1, 'albumId' => 1, 'privacy' => 1, 'photo.thumb.webFileLocation' => 1, 'photo.width' => 1, 'photo.height' => 1))->sort(array('ts' => -1)), false);
                if(count($results) > 0)
                    return $results;
                return array();
            }

        public function _getAllWithPhotoId($uid)
        {
            if(!$uid)
                return array();
            $mongo = $this->getDI()->getShared('mongo');
            $results = iterator_to_array($mongo->photos->find(array('uid' => (int)$uid), array('uid' => 1, 'photo.webFileLocation' => 1, 'caption' => 1, 'albumId' => 1, 'privacy' => 1, 'photo.thumb.webFileLocation' => 1, 'photo.width' => 1, 'photo.height' => 1))->sort(array('ts' => -1)), false);
            if(count($results) > 0 )
                return $results;
            return array();
        }

        public function _getAlbumPhotos($uid, $albumId)
            {
                if(!$uid || !$albumId)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $results = iterator_to_array($mongo->photos->find(array('uid' => (int)$uid, 'albumId' => $albumId)), false);
                if(count($results) > 0)
                    return $results;
                return array();
            }


        public function _deleteAlbumPhotos($uid, $albumId)
            {
                if(!$uid || !$albumId)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photos->remove(array('uid' => (int)$uid, 'albumId' => $albumId)))
                    return true;
                return false;
            }

        public function _getPhoto($uid, $photoId)
            {
                if(!$uid || !$photoId)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $result = iterator_to_array($mongo->photos->find(array('uid' => (int)$uid, '_id' => new MongoId($photoId))), false);
                if(count($result) > 0)
                    return $result[0];
                return array();
            }

        public function __deletePhotos($uid) //only administrator & bot handle this for deleting users.
            {
                if(!$uid)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photos->remove(array('uid' => (int)$uid)))
                    return true;
                return false;
            }

        public function _getPhotosWithTag($uid, $tagName)
            {
                if(!$uid || !$tagName)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $results = iterator_to_array($mongo->photos->find(array('uid' => (int)$uid, 'tags' => $tagName)), false);
                if(count($results))
                    return $results;
                return array();
            }

        public function _mergeTagsWithPhoto($uid, $photoTags)
            {
                if(is_array($photoTags))
                    {
                        foreach($photoTags as $key => $val)
                            {
                                $result = $this->_getPhotosWithTag($uid, $photoTags[$key]['tagName']);
                                $photoTags[$key]['photo'] = $result[0]['photo'];
                            }
                        return $photoTags;
                    }
                return $photoTags;
            }

    }