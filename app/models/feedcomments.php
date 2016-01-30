<?php


namespace app\models;

use MongoDate,
    MongoId;

class feedcomments extends \Phalcon\Mvc\Collection
    {
        public $uid;
        public $ts;
        public $feedId;
        public $comment;
        public $media;
        /*
        Comment:
            Text
            Media
                ...
        */

        public function getSource()
            {
                return 'feedcomments';
            }

        public function _get($feedId, $limit = 25, $ts = null)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $results = iterator_to_array($mongo->feedcomments->find(array('feedId' => $feedId))->limit($limit)->sort(array('ts' => 1)), false);
                if(count($results) > 0)
                    return $results;
                return array();
            }

        public function _create($uid, $id, $comment, $media)
            {
                if(!$uid || !$id || !$comment)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $feedcomment = new \stdClass();
                $feedcomment->ts = new MongoDate();
                $feedcomment->uid = $uid;
                $feedcomment->feedId = $id;
                $feedcomment->comment = htmlentities($comment);
                $feedcomment->media = $media;
                if($mongo->feedcomments->insert($feedcomment)['ok'] == 1)
                    return $feedcomment;
                return false;
            }

        public function _delete($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->feedcomments->remove(array('uid' => (int)$uid, '_id' => new MongoId($id)))['ok'] == 1)
                    return true;
                return false;
            }

        public function _deleteAll($feedId)
            {
                if(!$feedId)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $results = $mongo->feedcomments->remove(array('feedId' => $feedId));
                if($results['ok'] == 1)
                    return true;
                return false;
            }
    }