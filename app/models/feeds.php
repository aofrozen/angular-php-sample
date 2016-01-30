<?php

namespace app\models;

use MongoDate,
    MongoId;

class feeds extends \Phalcon\Mvc\Collection
    {
        public $ts;
        public $uid; //user's property
        public $fid; //writer //need to improve this for friend comment
        public $type; //feed, photo or journal
        public $post;
        public $media;
        public $privacy;

    /*
     * Data
     * Journal
     * Status
     * Photos
     * Share
     * Write
     */
    /*
     * Data
     *  * Type
     *  * UID [For comment]
     *  * HTML
     *
     */
        public function _getFeedIdsFromData($source, $feedIdsListA = null) //complete and require to test more.
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
                                $feedIdsListA = $this->_getFeedIdsFromData($val, $feedIdsListA);
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

        public function _getCommentsWithFeedIds($feedIds) //complete and require to test more.
        {
            $feedCommentsListA = array();
            $mongo = $this->getDI()->getShared('mongo');
            $feedIdsCount = count($feedIds);
            for($x=0;$x<$feedIdsCount;$x++)
            {
                $mongoId = $feedIds[$x];
                $feedComments = iterator_to_array($mongo->feedcomments->find(array('feedId' => $mongoId))->sort(array('ts' => -1)), false);
                $feedCommentsListA[$feedIds[$x]] = array();
                $feedCommentsListA[$feedIds[$x]]['comments'] = $feedComments;
            }
            return $feedCommentsListA;
        }

        public function _mergeFeedsWithComments($source)
        {
            $feedIds = $this->_getFeedIdsFromData($source); //ok
            $comments = $this->_getCommentsWithFeedIds($feedIds);
            $newSource = $this->_addCommentsToData($source, $comments);
            return $newSource;
        }

        public function getSource()
            {
                return 'feeds';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function _create($uid, $type, $post, $media = null, $privacy = null)
            {
                if(!$uid || !$type || !$post)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $feeds = new \stdClass();
                $feeds->ts = new MongoDate();
                $feeds->uid = $uid;
                $feeds->type = $type; // type = simple, like, photo, comment, share or journal
                $feeds->post = $post;
                $feeds->media = $media;
                $feeds->privacy = $privacy;
                /*
                 * Data structure
                 *
                 * Text & HTML
                 *
                 */
                if ($mongo->feeds->insert($feeds)['ok'] == 1)
                    return $feeds;
                return false;
            }

        public function _setPrivacy($uid, $id, $privacy)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                if($mongo->feeds->update(array('uid' => $uid, '_id' => new MongoId($id)), array('privacy' => array('$set' => $privacy))))
                    return true;
                return false;
            }

        public function getSelectUserFeeds($uid, $filter, $ts) //following user. It can be used for home's feeds modal and profile page
            {
                if(!$uid)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $feeds = iterator_to_array($mongo->feeds->find(array('uid' => (int)$uid))->limit(10)->sort(array('ts' => -1)), false);
                if(count($feeds) > 0)
                    return $feeds;
                return array();
            }

        public function getSelectUsersFeeds($_uid, $uids, $filter) //following users. It can be used for only home page
            {
                if(!$uids)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $uidsCount = count($uids);
                $feeds = array();
                $maxItems = 20;
                $itemsCount = 0;
                for($x = 0; $x < $uidsCount; $x++)
                    {
                        if($_uid == $uids[$x]['followUid'])
                            {

                                $dateTime= new \DateTime('-45 second');
                                $items = iterator_to_array($mongo->feeds->find(array('uid' => $uids[$x]['followUid'], 'ts' => array('$gt' => new MongoDate($dateTime->getTimestamp()))))->limit(5)->sort(array('ts' => -1)), false);
                            }else{
                                $items = iterator_to_array($mongo->feeds->find(array('uid' => $uids[$x]['followUid']))->limit(5)->sort(array('ts' => -1)), false);
                        }

                        $itemsCount += count($items);
                        if(count($items) > 0)
                            {
                                if($_uid == $uids[$x]['followUid'])
                                    {
                                        $feeds = array_merge($feeds, $items);
                                    }elseif($_uid != $uids[$x]['followUid']){
                                        $feeds = array_merge($feeds, $items);
                                }
                            }
                        if($itemsCount > $maxItems)
                            break;
                    }
                if(count($feeds) > 0)
                    return $feeds;
                return array();
            }

        public function getHomeFeeds($uids)
            {
                if(!$uids)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $feeds = iterator_to_array($mongo->feeds->find(array('uid' => array('$in' => $uids)))->limit(20)->sort(array('ts' => -1)), false);
                if(count($feeds) > 0)
                    return $feeds;
                return array();
            }

        public function _get($uid, $ts)
            {
                if(!$uid)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $feeds = iterator_to_array($mongo->feeds->find(array('uid' => (int)$uid))->limit(20)->sort(array('ts' => -1)), false);
                if(count($feeds) > 0)
                    return $feeds;
                return array();
            }

        public function _getItem($id)
            {
                if(!$id)
                    return array();
                $mongo = $this->getDI()->getShared('mongo');
                $feedItem = iterator_to_array($mongo->feeds->find(array('_id' => new MongoId($id)))->limit(1)->sort(array('ts' => -1)), false);
                if(count($feedItem) > 0)
                    return $feedItem[0];
                return array();
            }

        public function _delete($uid, $id)
            {
                if(!$uid || !$id)
                    return false;
                $mongo = $this->getDI()->getShared('mongo');
                $results = $mongo->feeds->remove(array('uid' => $uid, '_id' => new MongoId($id)));
                if($results['ok'] == 1 && $results['n'] > 0)
                    return true;
                return false;
            }
    }