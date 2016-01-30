<?php


namespace app\models;

use MongoId,
    MongoDate,
    \Phalcon\Mvc\Collection;

class phototaggroups extends collection
{
    public $ts;
    public $uid;
    public $tagName;
    public $photoCount;

    public function getSource()
    {
        return 'phototaggroups';
    }

    public function _save($uid, $tags)
        {
            if(is_array($tags))
                {
                    $tagsCount = count($tags);
                    for($x=0;$x<$tagsCount;$x++)
                        {
                            $tagName = $tags[$x];
                            if($this->exists($uid, $tagName))
                                {
                                    $this->_updatePhotoCount($uid, $tagName, 1);
                                }else{
                                    $this->_create($uid, $tagName);

                            }
                        }
                }
        }

    public function _remove($uid, $tags)
        {
            if(is_array($tags))
                {
                    $tagsCount = count($tags);
                    for($x=0;$x<$tagsCount;$x++)
                        {
                            $tagName = $tags[$x];
                            $result = $this->exists($uid, $tagName);
                            if($result['photoCount'] == 1)
                                {
                                    $this->_delete($uid, $tagName);
                                }else{
                                    $this->_updatePhotoCount($uid, $tagName, -1);
                                }
                        }
                }
        }

    private function _create($uid, $tagName)
    {
        if(!$uid || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $phototaggroup = new \stdClass();
        $phototaggroup->ts = new MongoDate();
        $phototaggroup->uid = $uid;
        $phototaggroup->tagName = $tagName;
        $phototaggroup->photoCount = 1;
        if($mongo->phototaggroups->insert($phototaggroup)['ok'] == 1)
            return $phototaggroup;
        return false;
    }

    private function _updatePhotoCount($uid, $tagName, $count)
        {
            if(!$uid || !$tagName)
                return false;
            $mongo = $this->getDI()->getShared('mongo');
            if($mongo->phototaggroups->update(array('uid' => (int)$uid, 'tagName' => (string)$tagName), array('$inc' => array('photoCount' => (int)$count)))['ok'] == 1)
                return true;
            return false;
        }

    public function _delete($uid, $tagName)
    {
        if(!$uid || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        if($mongo->phototaggroups->remove(array('uid' => (int)$uid, 'tagName' => $tagName)))
            return true;
        return false;
    }

    public function _get($uid)
    {
        if(!$uid)
            return array();
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->phototaggroups->find(array('uid' => (int)$uid)), false);
        if(count($results) > 0)
            return $results;
        return array();
    }

    private function exists($uid, $tagName)
    {
        if(!$uid || !$tagName)
            return false;
        $mongo = $this->getDI()->getShared('mongo');
        $results = iterator_to_array($mongo->phototaggroups->find(array('uid' => (int)$uid, 'tagName' => (string)$tagName)), false);
        if(count($results))
            return $results[0];
        return false;
    }
}