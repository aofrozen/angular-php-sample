<?php

namespace app\models;

use MongoDate,
    MongoId;

class photocomments extends \Phalcon\Mvc\Collection
    {

        public $uid;
        public $ts;
        public $photoId;
        public $comment;
        public $media;

        public function getSource()
            {
                return 'photocomments';
            }

        public function _getPhotoComments($photoId, $page = 0)
            {
                if(!$photoId)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $results = iterator_to_array($mongo->photocomments->find(array('photoId' => $photoId))->sort(array('ts' => -1))->limit(10), false);
                if(count($results) > 0)
                    return $results;
                return array();
            }

        public function _create($uid, $photoId, $comment, $media = null)
            {
                if(!$uid || !$photoId || !$comment)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $photoComment = new \stdClass();
                $photoComment->ts = new MongoDate();
                $photoComment->uid = $uid;
                $photoComment->photoId = $photoId;
                $photoComment->comment = htmlentities($comment);
                $photoComment->media = $media;
                if($mongo->photocomments->insert($photoComment)['ok'] == 1)
                    return $photoComment;
                return false;
            }

        public function _deleteAllCommentsOnPhoto($uid, $photoId)
            {
                if(!$uid || !$photoId)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photocomments->remove(array('uid' => (int)$uid, 'photoId' => $photoId))['ok'] === 1)
                    return true;
                return false;
            }

        public function _deleteComment($uid, $commentId, $photoId)
            {
                if(!$uid || !$commentId || !$photoId)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photocomments->remove(array('uid' => (int)$uid, '_id' => new MongoId($commentId), 'photoId' => $photoId))['ok'] == 1)
                    {
                        return true;
                    }else{
                    return false;
                }
            }

        public function _set($uid, $photoId, $comment, $media)
            {
                if(!$uid || !$photoId)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->photocomments->update(array('uid' => $uid, 'photoId' => $photoId), array('$set' => array('comment' => $comment, 'media' => $media))))
                    return true;
                return false;
            }
        public function _getPhotoIdsFromData($source, $feedIdsListA = null) //complete and require to test more.
            {
                if (!is_array($feedIdsListA))
                    $feedIdsListA = array();
                $total = count($source);
                for ($x = 0; $x < $total; $x++)
                    {
                        if (array_key_exists($x, $source))
                            {
                                if(is_array($source[$x]))
                                    {
                                        foreach ($source[$x] as $key => $val)
                                            {
                                                if (gettype($val) === 'array')
                                                    {
                                                        $feedIdsListA = $this->_getPhotoIdsFromData($val, $feedIdsListA);
                                                    } elseif ($key == '_id')
                                                    {
                                                        $_id = $val->{'$id'};
                                                        if (!in_array($_id, $feedIdsListA))
                                                            array_push($feedIdsListA, $_id);
                                                    }
                                            }
                                    }
                            }
                    }
                return $feedIdsListA;
            }

        public function _addCommentsToData($source, $comments) //complete and require to test more.
            {
                $newSource = $source;
                $total = count($source);
                for ($x = 0; $x < $total; $x++)
                    {
                        if (array_key_exists($x, $source))
                            {
                                if(is_array($source[$x]))
                                    {
                                        foreach ($source[$x] as $key => $val)
                                            {
                                                if (is_array($val))
                                                    {
                                                        $newSource[$x][$key] = $this->_addCommentsToData($val, $comments);
                                                    } elseif ($key == '_id')
                                                    {
                                                        if (is_array($newSource[$x]))
                                                            {
                                                                $newSource[$x]['comments'] = array();
                                                                $newSource[$x]['comments'] = $comments[$val->{'$id'}]['comments'];
                                                            }

                                                    }
                                            }
                                    }
                            }
                    }

                return $newSource;
            }

        public function _getCommentsWithPhotoIds($feedIds) //complete and require to test more.
            {
                $feedCommentsListA = array();
                $mongo = $this->getDI()->getShared('mongo');
                $feedIdsCount = count($feedIds);
                for($x=0;$x<$feedIdsCount;$x++)
                    {
                        $mongoId = $feedIds[$x];
                        $feedComments = iterator_to_array($mongo->photocomments->find(array('photoId' => $mongoId))->sort(array('ts' => -1)), false);
                        $feedCommentsListA[$feedIds[$x]] = array();
                        $feedCommentsListA[$feedIds[$x]]['comments'] = $feedComments;
                    }
                return $feedCommentsListA;
            }

        public function _mergePhotosWithComments($source)
            {
                $feedIds = $this->_getPhotoIdsFromData($source); //ok
                $comments = $this->_getCommentsWithPhotoIds($feedIds);
                $newSource = $this->_addCommentsToData($source, $comments);
                return $newSource;
            }
    }