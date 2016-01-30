<?php

use app\controllers\AuthController,
    app\models\feeds,
    app\models\feedcomments,
    app\models\friends,
    app\models\feedfollows,
    app\models\metafriends,
    app\models\notifications,
    app\models\profiles,
    app\models\users,
    app\models\privacy,
    app\models\embeds,
    app\models\photos,
    app\models\photoalbums,
    app\models\photocomments,
    app\models\phototaggroups,
    app\controllers\NotificationController,
    app\library\session\userSessions,
        app\library\fs\fileStorage as fs;

/*
 * Micro doesn't support any headers (ex: cookies and redirection)
 */

$app = new \Phalcon\Mvc\Micro($di);

$app->get('/ajax/test', function ()
    {
        return 'Powered By Phalcon API';
    });

$app->post('/ajax/test', function ()
    {
        return 'Powered By Phalcon API';
    });

$app->delete('/ajax/test', function ()
    {
        return 'Powered By Phalcon API';
    });

$app->post('/ajax/recover', function () use ($app)
    {
        $mongo = $app->getDI()->getShared('mongo');
        $json = $app->request->getJsonRawBody();
        $user = $mongo->users->find(array('email' => $json->email), array('email' => 1));
        $user = iterator_to_array($user, false);
        if (count($user) > 0)
            {
                $app->response->setJsonContent(array('success' => true));
            } else
            {
                $app->response->setJsonContent(array('success' => false, 'message' => "Email doesn't exist."));
            }
        $app->response->send();
    });

$app->put('/ajax/recover/change', function () use ($app)
    {
        $mongo = $app->getDI()->getShared('mongo');
    });

/* Embedly */
$app->get('/ajax/token', function () use ($app)
    {
        session_start();
        $tokenKey = $app->security->getTokenKey();
        $token = $app->security->getToken();
        session_write_close();
        $app->response->setJsonContent(array('tokens' => array('key' => $tokenKey, 'token' => $token)));
        $app->response->send();
    });

$app->post('/ajax/embed', function () use ($app)
    {
        //if($app->security->checkToken($tokenKey, $tokenVal))
        //{
        $source = $app->request->getJsonRawBody();

        if (filter_var($source->url, FILTER_VALIDATE_URL) === false)
            {
                $app->response->setJsonContent(array('embed' => array(), 'alerts' => array('type' => 'danger', 'message' => 'URL is not valid.')));
                $app->response->send();
                exit;
            }
        $source->url = $app->c->setOnlyHttp($source->url);
        $embedSource = (new embeds())->_get($source->url);
        if ($embedSource === false)
            {
                $embedSource = $app->embedly->oembed($source->url);
                if(!isset($embedSource->url))
                    {
                        $embedSource->url = $source->url;
                    }
                (new embeds())->_create($embedSource); //fix this shit
            }
        $app->response->setJsonContent(array('embed' => $embedSource));
        $app->response->send();
        //}else{

        //echo 'Unauthorized!';
        //}
    });

/* Messages */
$app->get('/ajax/messages/{uid:[0-9]+}', function ($fid) use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $data = (new messages())->_get($uid, $fid);

    });

$app->delete('/ajax/messages', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
    });

$app->post('/ajax/messages', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
    });

/* User */
$app->get('/ajax/user', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $profileData = (new profiles())->_get($uid);
        $profileWall = '';
        $profileAvatar = '';
        $firstName = '';
        $lastName = '';
        $username = '';
        $key = '';
        if (isset($profileData['profileWall']['webFileLocation']))
            $profileWall = $app->c->set($profileData['profileWall']['webFileLocation'], null, 'http://', '?' . time());
        if (isset($profileData['profileAvatar']['webFileLocation']))
            $profileAvatar = $app->c->set($profileData['profileAvatar']['webFileLocation'], null, 'http://', '?' . time());
        if (isset($profileData['username']))
            $username = $profileData['username'];
        if (isset($profileData['fName']))
            $firstName = $profileData['fName'];
        if (isset($profileData['lName']))
            $lastName = $profileData['lName'];
        if(isset($profileData['key']))
            $key = $profileData['key'];
        $friends = (new friends())->_get($uid);
        $profile = new profiles();
        $friends = $profile->_mergeProfilesWithUserIds($friends);
        $follows = (new feedfollows())->_getFeedFollows($uid);
        $follows = $profile->_mergeProfilesWithUserIdsCache($follows);
        $user = array('user' => array('uid' => $uid, 'key' => $key, 'avatar' => $profileAvatar, 'wall' => $profileWall, 'username' => $username, 'name' => $firstName . ' ' . $lastName, 'friends' => $friends, 'follows' => $follows));
        $app->response->setJsonContent($user);
        $app->response->send();
    });

/* Feeds */
/* Home and Profile */
$app->get('/ajax/feeds/follows', function() use ($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $feedFollows = (new feedfollows())->_getFeedFollows($_uid);
        $app->response->setContent(json_encode(array('feedFollows' => $feedFollows), JSON_PRETTY_PRINT));
        $app->response->send();
    });


$app->get('/ajax/feeds/home', function() use($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $uids = array($_uid);
        $feedFollows = (new feedFollows())->_getFeedFollows($_uid);
        $feedFollowsCount = count($feedFollows);
        for($x = 0; $x < $feedFollowsCount; $x++)
            {
                array_push($uids, $feedFollows[$x]['followUid']);
            }
        $homeFeedData = (new feeds())->getHomeFeeds($uids);
        $homeFeedData = (new feeds())->_mergeFeedsWithComments($homeFeedData);
        $feedData = (new profiles())->_mergeProfilesWithUserIds($homeFeedData);
        //merge with comments
        $app->response->setContent(json_encode(array('feeds' => $feedData), JSON_PRETTY_PRINT));
        $app->response->send();
    });

$app->get('/ajax/feeds/user/{uid}/filter/{filter}/next/{ts}', function($uid, $filter, $ts) use($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $homeFeedData = (new feeds())->getSelectUserFeeds($uid, $filter, $ts);
        $homeFeedData = (new feeds())->_mergeFeedsWithComments($homeFeedData);
        $feedData = (new profiles())->_mergeProfilesWithUserIds($homeFeedData);
        //merge with comments
        $app->response->setContent(json_encode(array('feeds' => $feedData), JSON_PRETTY_PRINT));
        $app->response->send();
    });
$app->get('/ajax/feeds/filter/{filter}/next-user/{lastUserId}', function ($filter, $lastUserId) use ($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $followUids = array();
        $feedFollows = (new feedFollows())->_getFeedFollows($_uid);
        $feedFollowsCount = count($feedFollows);
        $writeFeedFollows = false;
        for($x = 0; $x < $feedFollowsCount; $x++)
            {
                if((int)$lastUserId === 0 || $lastUserId == $feedFollows[$x]['followUid'] || $writeFeedFollows === true)
                    {
                        $followUids[$x]['followUid'] =  $feedFollows[$x]['followUid'];
                        $followUids[$x]['lastRead'] = (isset($feedFollows[$x]['lastRead']) ? $feedFollows[$x]['lastRead'] : 0);
                        $writeFeedFollows = true;
                    }
            }
        $homeFeedData = (new feeds())->getSelectUsersFeeds($_uid, $followUids, $filter);
        $homeFeedData = (new feeds())->_mergeFeedsWithComments($homeFeedData);
        $homeFeedData = (new feeds())->_mergeFeedsWithComments($homeFeedData);
        $feedData = (new profiles())->_mergeProfilesWithUserIds($homeFeedData);
        //merge with comments
        $app->response->setContent(json_encode(array('feeds' => $feedData), JSON_PRETTY_PRINT));
        $app->response->send();
    });

$app->get('/ajax/feeds/profile/user/{uid}', function ($uid) use ($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if (isset($data->ts))
            {
                $ts = $data->ts;
            } else
            {
                $ts = 0;
            }
        $feedData = (new feeds())->_get($uid, $ts);
        $feedData = (new feeds())->_mergeFeedsWithComments($feedData);
        $feedData = (new profiles())->_mergeProfilesWithUserIds($feedData);
        $feeds = array('feeds' => $feedData);
        $app->response->setContent(json_encode($feeds, JSON_PRETTY_PRINT));
        $app->response->send();
    });

$app->get('/ajax/feeds/item/{id}', function($id) use($app){
    $_uid = (new userSessions())->requiredLogin(true);
    $feedItemSource[] = (new feeds())->_getItem($id);
    $feedItemSource = (new profiles())->_mergeProfilesWithUserIds($feedItemSource);
    $feedItemSource = (new feeds())->_mergeFeedsWithComments($feedItemSource);
    $feedItem = array('feedItem' => $feedItemSource[0]);
    $app->response->setJsonContent($feedItem);
    $app->response->send();
});

//post feed item
$app->post('/ajax/feeds', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        /*
         * uid
         * type : ??? friend or owner
         * authorID: uid
         * writingBody - message
         * media:
         * Video
         *  caption
         *  type: youtube, vimeo, dailymotion (no), vine.co and Break (no)
         *  url
         * Photo
         *  caption
         *
         *
         *
         *
         */
        $feedPost = $data;
        $feedItem = (new feeds())->_create($uid, $feedPost->type, $feedPost->post, $feedPost->media);
        if($feedItem)
        {
            $app->response->setContent(json_encode(array('feeds' => array('uid' => $uid, 'success' => true, 'items' => $feedItem))));
            $app->response->send();
            exit;
        }else{
            $app->response->setContent(json_encode(array('feeds' => array('uid' => $uid, 'success' => false, 'items' => null), 'alerts' => array('message' => "Not able to post feed.", 'type' => 'error'))));
            $app->response->send();
            exit;
        }
    });

$app->delete('/ajax/feeds', function () use ($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if (isset($data->id))
            {
                $id = $data->id;
            } else
            {
                return false;
            }
        /*
         *
         * First delete comments
         * Delete feed item
         *
         */
        if(!(new feedcomments())->_deleteAll($id))
            {
                $app->response->setContent(json_encode(array('feeds' => array('uid' => $_uid, 'success' => false), 'alerts' => array('type' => 'danger', 'message' => 'Not able to delete the feed item [101].')), JSON_PRETTY_PRINT));
                $app->response->send();
                exit;
            }
        if(!(new feeds())->_delete($_uid, $id))
            {
                $app->response->setContent(json_encode(array('feeds' => array('uid' => $_uid, 'success' => false), 'alerts' => array('type' => 'danger', 'message' => 'Not able to delete the feed item [102].')), JSON_PRETTY_PRINT));
                $app->response->send();
                exit;
            }
        $app->response->setContent(json_encode(array('feeds' => array('uid' => $_uid, 'success' => true)), JSON_PRETTY_PRINT));
        $app->response->send();
    });

$app->post('/ajax/feeds/comments', function() use($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if(!isset($data->id) || !isset($data->comment))
            return false;
        if(!isset($data->media))
            $data->media = null;
        $feedComment = (new feedcomments())->_create($_uid, $data->id, $data->comment, $data->media);
        if($feedComment)
            {
                $app->response->setContent(json_encode(array('feeds' => array('uid' => $_uid, 'success' => true, 'items' => array('comments' => $feedComment))), JSON_PRETTY_PRINT));
                $app->response->send();
            }else{
                $app->response->setContent(json_encode(array('feeds' => array('uid' => $_uid, 'success' => false, 'items' => null)), JSON_PRETTY_PRINT));
                $app->response->send();
        }
    });

$app->delete('/ajax/feeds/comments', function() use($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if(!isset($data->id))
            return false;
        if((new feedcomments())->_delete($_uid, $data->id))
            {
                $app->response->setContent(json_encode(array('feeds' => array('uid' => $_uid, 'success' => true)), JSON_PRETTY_PRINT));
                $app->response->send();
            }else{
                $app->response->setContent(json_encode(array('feeds' => array('uid' => $_uid, 'success' => true)), JSON_PRETTY_PRINT));
                $app->response->send();
        }
    });


$app->get('/ajax/feeds/item/{feedId}/comments', function($feedId) use($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $feedComments = (new feedcomments())->_get($feedId);
        $feedComments = (new profiles())->_mergeProfilesWithUserIds($feedComments);
        $app->response->setContent(json_encode(array(array('feeds' => array('uid' => $_uid, 'items' => $feedComments))), JSON_PRETTY_PRINT));
        $app->response->send();
    });

/* Friends */
$app->get('/ajax/friends/{pid}', function ($pid) use ($app)
    {
        if (!isset($pid))
            return false;
        $uid = (new userSessions())->requiredLogin(true);
        $friendsList = (new profiles())->_mergeProfilesWithUserIds((new friends())->_get($pid));
        $app->response->setJsonContent(array('friends' => array('uid' => (int)$pid, 'list' => $friendsList)));
        $app->response->send();

    });

$app->post('/ajax/friends', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if (!isset($data->fid))
            return false;
        if ($data->fid == $uid)
            {
                $app->response->setJsonContent(array("friends" => array('addFriendButtonName' => 'Add Friend'), 'alerts' => array('type' => 'danger', 'message' => 'Not able to send a friend request due to this page is you.')));
                $app->response->send();
                exit;
            }
        $notification = (new notifications())->_exist($data->fid, 'friend.request', $uid);
        if ($notification) //(new friends)->_exist($uid, $data->fid)
            {
                if ((new notifications())->_delete($data->fid, $notification->_id))
                    {
                        $app->response->setJsonContent(array("friends" => array('addFriendButtonName' => 'Add Friend')));
                        $app->response->send();
                    } else
                    {
                        $app->response->setJsonContent(array("friends" => array('addFriendButtonName' => 'Friend Request Sent'), 'alerts' => array('type' => 'danger', 'message' => 'Not able to remove the friend request. Try it again later.')));
                        $app->response->send();
                    }
            } elseif ((new friends())->_exist($uid, $data->fid) == false)
            {
                $notification = (new notifications())->_exist($uid, 'friend.request', $data->fid);
                if ($notification) //check if user already sent you a friend request.
                    {
                        $app->response->setJsonContent(array("friends" => array('addFriendButtonName' => 'Add Friend'), 'alerts' => array('type' => 'warning', 'message' => 'User already sent a friend request. Check your notifications.')));
                        $app->response->send();
                    } else
                    {
                        if ((new notifications())->_create($data->fid, 'friend.request', $uid, array('')))
                            {
                                $app->response->setJsonContent(array("friends" => array('addFriendButtonName' => 'Friend Request Sent')));
                                $app->response->send();
                            } else
                            {
                                //failed to add
                                $app->response->setJsonContent(array("friends" => array('addFriendButtonName' => 'Friend Request Sent'), 'alerts' => array('type' => 'danger', 'message' => 'Not able to send a friend request. Try it again later.')));
                                $app->response->send();
                            }
                    }

            } else
            {
                //Already Friend then unfriend
                $app->response-->setContent(json_encode(array("friends" => array('addFriendButtonName' => 'Friends'), 'alerts' => array('type' => 'danger', 'message' => 'Not able to send a friend request. Try it again later.')), JSON_PRETTY_PRINT));
                $app->response->send();
            }
    });

$app->delete('/ajax/friends', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if (!isset($data->fid))
            return false;
        if ((new friends())->_delete($uid, $data->fid) && (new friends())->_delete($data->fid, $uid))
            {
                (new feedfollows())->_delete($uid, $data->fid);
                (new feedfollows())->_delete($data->fid, $uid);
                (new metafriends())->_updateFriendCount($uid, $data->fid, -1);
            }
        $app->response->setContent(json_encode(array("friends" => array('addFriendButtonName' => 'Add Friend')), JSON_PRETTY_PRINT));
        $app->response->send();
    });

/* About */
$app->get('/ajax/about/{uid:[0-9]+}', function ($uid) use ($app)
    {
        if (!isset($uid))
            return false;
        $profileData = (new profiles())->_get($uid);
        $profileData['name'] = $profileData['fName'] . ' ' . $profileData['lName'];
        $userData = (new users())->_get($uid);
        $aboutData = array(
            'gender' => $profileData['gender'],
            'email' => $userData['email'],
            'birthday' => '',
            'mobileNumbers' => $profileData['mobileNumbers']
        );
        $app->response->setContent(json_encode(array('about' => $aboutData), JSON_PRETTY_PRINT));
        $app->response->send();
    });

/* Notifications */
$app->get('/ajax/notifications', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $notifications = ((new profiles())->_mergeProfilesWithUserIds((new notifications())->_get($uid)));
        $app->response->setContent(json_encode(array('notifications' => $notifications), JSON_PRETTY_PRINT));
        $app->response->send();
    });

$app->put('/ajax/notifications', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if (!isset($data->id))
            return false;
        if ($uid)
            {
                $notification = (new notifications())->_getItem($uid, $data->id);
                switch ($notification->type)
                    {
                    case 'friend.request':
                            $fidKey = (new profiles())->_getKey($notification->fid);
                            $uidKey = (new profiles())->_getKey($notification->uid);
                            if ((new friends())->_create($notification->fid, $notification->uid, $uidKey) && (new friends())->_create($notification->uid, $notification->fid, $fidKey))
                                {
                                    (new feedfollows())->_create($notification->uid, $notification->fid);
                                    (new feedfollows())->_create($notification->fid, $notification->uid);
                                    (new metafriends())->_updateFriendCount($notification->fid, $notification->uid, 1);
                                }
                            $notification = (new notifications())->_delete($uid, $data->id);
                            break;
                    }
            } else
            {
                //offline
            }
    });

$app->delete('/ajax/notifications', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if (!isset($data->id))
            return false;
        $app->response->setContent(json_encode(array('notifications' => (new notifications())->_delete($uid, $data->id)), JSON_PRETTY_PRINT));
        $app->response->send();
    });
/* Home */
$app->get('/ajax/home', function () use ($app)
    {
        $uid = (new userSessions())->requiredLogin(true);
        $homeData = (new profiles())->_get($uid);
        $homeWall = '';
        $profileAvatar = '';
        if (is_array($homeData['homeWall']))
            $homeWall = 'http://' . $homeData['homeWall']['webFileLocation'] . '?' . time();
        if (is_array($homeData['profileAvatar']))
            $profileAvatar = 'http://' . $homeData['profileAvatar']['webFileLocation'] . '?' . time();
        $home = array(
            'uid' => $uid,
            'username' => $homeData['username'],
            'name' => $homeData['fName'] . ' ' . $homeData['lName'],
            'wall' => $homeWall,
            'avatar' => $profileAvatar
        );
        $app->response->setContent(json_encode(array('home' => $home), JSON_PRETTY_PRINT));
        $app->response->send();
    });

/* Profile */
$app->get('/ajax/profile/{fid:[0-9]+}', function ($pid) use ($app) //JSON OK
    {
        //privacy

        $uid = (new userSessions())->getUserID();

        /* Profile */
        $profileData = (new profiles())->_get($pid);
        if (count($profileData) < 1)
            return false;
        $profileWall = '';
        $profileAvatar = '';
        $username = '';
        $firstName = '';
        $lastName = '';
        $description = '';
        $wallPosition = 'center';
        $wallRotation = 0;
        if (isset($profileData['profileWall']['webFileLocation']))
            $profileWall = $app->c->set($profileData['profileWall']['webFileLocation'], null, 'http://', '?' . time());
        if (isset($profileData['profileAvatar']['webFileLocation']))
            $profileAvatar = $app->c->set($profileData['profileAvatar']['webFileLocation'], null, 'http://', '?' . time());
        if (isset($profileData['username']))
            $username = $profileData['username'];
        if (isset($profileData['fName']))

            $firstName = $profileData['fName'];
        if (isset($profileData['lName']))
            $lastName = $profileData['lName'];
        if (isset($profileData['description']))
            $description = $profileData['description'];
        if (isset($profileData['profileWall']['position']))
            $wallPosition = $profileData['profileWall']['position'];
        if (isset($profileData['profileWall']['rotation']))
            $wallRotation = $profileData['profileWall']['rotation'];
        $profile = array(
            'uid' => $pid,
            'username' => $username,
            'name' => $firstName . ' ' . $lastName,
            'description' => $description,
            'wall' => $profileWall,
            'wallPosition' => $wallPosition,
            'wallRotation' => $wallRotation,
            'avatar' => $profileAvatar
        );
        if ($uid == $pid)
            {
                $owner = true;
            } else
            {
                $owner = false;
            }
        /* Add Friend Button Name */
        $addFriendButtonName = 'Add Friend';
        if ((new notifications())->_exist($pid, 'friend.request', $uid))
            {
                $addFriendButtonName = 'Friend Request Sent';
            } elseif ((new friends())->_exist($pid, $uid))
            {
                $addFriendButtonName = 'Friends';
            }
        $metafriend = (new metafriends())->_get($pid);
        $friends = array('uid' => $pid, 'addFriendButtonName' => $addFriendButtonName, 'friendCount' => $metafriend['stats']['friendCount']);
        /* END */

        $app->response->setContent(json_encode(array('friends' => $friends, 'profile' => $profile), JSON_PRETTY_PRINT));
        $app->response->send();
    });

$app->put('/ajax/profile/wall', function() use($app){
    $_uid = (new userSessions())->requiredLogin(true);
    $data = $app->request->getJsonRawBody();
    /*
     * Edit wall position
     * Edit wall rotation
     */
    //clear before set home styles
    if($data->wallRotation === true)
        (new profiles())->rotateProfileWall($_uid);
    print_r($data);
    if((new profiles())->setProfileStyles($_uid, $data))
    {
        $app->response->setContent(json_encode(array('profiles' => array('uid' => $_uid, 'success' => true)), JSON_PRETTY_PRINT));
        $app->response->send();
    }else{
        $app->response->setContent(json_encode(array('profiles' => array('uid' => $_uid, 'success' => false)), JSON_PRETTY_PRINT));
        $app->response->send();
    }
});

$app->put('/ajax/home/wall', function() use($app){
    $_uid = (new userSessions())->requiredLogin(true);
    $data = $app->request->getJsonRawBody();
    print_r($data);
    /*
     * Edit wall position
     * Edit wall rotation
     */
    //clear before set home styles
    if($data->wallRotation === true)
        (new profiles())->rotateHomeWall($_uid);
    if((new profiles())->setHomeStyles($_uid, $data))
    {
        $app->response->setContent(json_encode(array('home' => array('uid' => $_uid, 'success' => true)), JSON_PRETTY_PRINT));
        $app->response->send();
    }else{
        $app->response->setContent(json_encode(array('home' => array('uid' => $_uid, 'success' => false)), JSON_PRETTY_PRINT));
        $app->response->send();
    }
});

/* Photos */
$app->get('/ajax/photos/{uid}/item/{id}', function($uid, $id) use ($app){
    if(!is_numeric($uid))
        $uid = (new profiles())->convertUsernameToPID($uid)['uid'];
    $_uid = (new userSessions())->getUserID();
    $photos = (new photos())->_getAllWithPhotoId($uid, $id);
    $photos = (new photocomments())->_mergePhotosWithComments($photos);
    $photos = (new profiles())->_mergeProfilesWithUserIds($photos);
    $app->response->setContent(json_encode(array('photos' => array('uid' => $uid, 'items' => $photos))));
    $app->response->send();
});

$app->get('/ajax/photos/{uid}/albums', function ($uid) use ($app)
    {
        $photoAlbums = (new phototaggroups())->_get($uid);
        $photoAlbums = (new photos())->_mergeTagsWithPhoto($uid, $photoAlbums);
        $app->response->setContent(json_encode(array('photoAlbums' => array('uid' => $uid, 'items' => $photoAlbums))));
        $app->response->send();
    });

$app->get('/ajax/photos/{uid}/item/{id}/comments/{page}', function($uid, $id, $page) use ($app){
    $comments = (new photocomments())->_getPhotoComments($id, $page);
    $app->response->setContent(json_encode(array('photos' => array('uid' => $uid, 'photoId' => $id, 'comments' => $comments))));
    $app->response->send();
});

$app->post('/ajax/photos/item/comments', function() use($app){
    $data = $app->request->getJsonRawBody();
    $_uid = (new userSessions())->requiredLogin(true);
    $commentItemData[0] = (array)(new photocomments())->_create($_uid, $data->photoId, $data->comment);
    if($commentItemData)
    {
        $commentItemData = (new profiles())->_mergeProfilesWithUserIds($commentItemData);
        $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => true, 'items' => array('comments' => array($commentItemData[0]))), JSON_PRETTY_PRINT)));
        $app->response->send();
    }else{
        $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => false), 'alerts' => array('type' => 'warning', 'message' => 'Failed to post a comment.')), JSON_PRETTY_PRINT));
        $app->response->send();
    }
});

$app->put('/ajax/photos/item/comments', function() use($app){
    $data = $app->request->getJsonRawBody();
    $_uid = (new userSessions())->requiredLogin(true);
    if((new photocomments())->_update())
    {
        $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid))));
        $app->response->send();
    }else{
        $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid))));
        $app->response->send();
    }
});

$app->delete('/ajax/photos/item/comments', function() use($app){
    $data = $app->request->getJsonRawBody();
    $_uid = (new userSessions())->requiredLogin(true);
    if(!isset($data->photoId) || !isset($data->commentId))
        {
            $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => false), 'alerts' => array('type' => 'danger', 'message' => "This comment can't be deleted because it's photo ID or comment ID doesn't exist."))));
            $app->response->send();
        }
    if((new photocomments())->_deleteComment($_uid, $data->commentId, $data->photoId))
    {
        $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => true))));
        $app->response->send();
    }else{
        $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => false), 'alerts' => array('type' => 'danger', 'message' => "This comment can't be deleted."))));
        $app->response->send();
    }
});

$app->get('/ajax/photos/{uid}/albums/{tagName}', function ($uid, $tagName) use ($app)
    {
        $albumPhotos = (new photos())->_getPhotosWithTag($uid, $tagName);
        $albumPhotos = (new photocomments())->_mergePhotosWithComments($albumPhotos);
        $albumPhotos = (new profiles())->_mergeProfilesWithUserIds($albumPhotos);
        $app->response->setContent(json_encode(array('photos' => array('uid' => $uid, 'items' => $albumPhotos, 'tagName' => $tagName)), JSON_PRETTY_PRINT));
        $app->response->send();
    });

$app->post('/ajax/photos/album', function () use ($app) //needs to investigate this
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if((new photoalbums())->_create($_uid, $data->albumAvatar, $data->caption, $data->privacy))
            {
                $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => true)), JSON_PRETTY_PRINT));
                $app->response->send();
            }else{
                $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => false, 'alerts' => array('type' => 'error', 'message' => 'Failed to save'))), JSON_PRETTY_PRINT));
                $app->response->send();
        }
    });

$app->delete('/ajax/photos/{uid}/album/{id}', function ($uid, $id) use ($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        if((new photoalbums())->_delete($uid, $id))
            {
                $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => true)), JSON_PRETTY_PRINT));
                $app->response->send();
            }else{
                $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => false)), JSON_PRETTY_PRINT));
                $app->response->send();
        }

    });

$app->get('/ajax/photos/{uid}', function ($uid) use ($app)
    {
        $photos  = (new photos())->_getAll($uid);
        $photos = (new photocomments())->_mergePhotosWithComments($photos);
        $photos = (new profiles())->_mergeProfilesWithUserIds($photos);
        $app->response->setContent(json_encode(array('photos' => array('uid' => $uid, 'items' => $photos), JSON_PRETTY_PRINT)));
        $app->response->send();
    });

$app->put('/ajax/photos/item', function () use ($app) //photo item
    {
        $_uid = (new userSessions())->requiredLogin(true);
        /* Edit photo caption and privacy */
        $data = $app->request->getJsonRawBody();
        if((new photos())->_setPhoto($_uid, $data->_id->{'$id'}, $data->caption, $data->privacy, explode(',', $data->tags)))
            {
                $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid,), 'success' => true, 'alerts' => array('type' => 'success', 'message' => 'Saved')), JSON_PRETTY_PRINT));
                $app->response->send();
            }else{
                $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid), 'success' => false, 'alerts' => array('type' => 'warning', 'message' => 'Failed to save')), JSON_PRETTY_PRINT));
                $app->response->send();
        }
    });

$app->delete('/ajax/photos', function () use ($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        $photoData = (new photos())->_getPhoto($_uid, $data->photoId);
        if((new fs())->prepareDelete($photoData[photo]) === false)
            {
                $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => false, 'alerts' => array('type' => 'danger', 'message' => 'Failed to delete')))));
                $app->response->send();
                exit;
            }
        (new phototaggroups())->_remove($_uid, $photoData['tags']);
        //Delete photo file first before database
        if((new photos())->_deletePhoto($_uid, $data->photoId))
            {
                $app->response->setContent(json_encode(array('photos' => array('uid' => $_uid, 'success' => true)), JSON_PRETTY_PRINT));
                $app->response->send();
            }else{
                $app->response->setContent(json_encode(array('photo' => array('uid' => $_uid, 'success' => false, 'alerts' => array('type' => 'danger', 'message' => 'Failed to delete'))), JSON_PRETTY_PRINT));
                $app->response->send();
        }
    });

/* Settings */
$app->get('/ajax/settings', function () use ($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $authData['auth'] = (new users)->_get($_uid);
        $profileData['profile'] = (new profiles())->_get($_uid);
        $privacyData['privacy'] = (new privacy())->_get($_uid);
        $homeData = (new profiles())->_get($_uid);
        $homeWall = '';
        if (is_array($homeData['homeWall']))
            $homeWall = 'http://' . $homeData['homeWall']['webFileLocation'] . '?' . time();
        $homeDataB['home'] = array('homeWall' => $homeWall);
        return json_encode(array_merge($authData, $profileData, $privacyData, $homeDataB), JSON_PRETTY_PRINT);
    });

$app->put('/ajax/settings', function () use ($app)
    {
        $_uid = (new userSessions())->requiredLogin(true);
        $data = $app->request->getJsonRawBody();
        if((new users())->emailExists($data->auth->email, $_uid))
            {
                $app->response->setJsonContent(array('alerts' => array('message' => 'The email already exists on socialSample account.', 'type' => 'danger')));
                $app->response->send();
                return false;
            }
        if((new profiles())->usernameExists($data->profile->username, $_uid))
            {
                $app->response->setJsonContent(array('alerts' => array('message' => 'The username already exists on socialSample account.', 'type' => 'danger')));
                $app->response->send();
                return false;
            }
        //required to set them lowercase
        $data->profile->username = strtolower($data->profile->username);
        $data->auth->email = strtolower($data->auth->email);
        $profile = (new profiles())->_set($_uid, $data->profile);
        $auth = (new users())->_set($_uid, $data->auth);
        if ($profile && $auth)
            {
                $authData['auth'] = (new users())->_get($_uid);
                $profileData['profile'] = (new profiles())->_get($_uid);
                $privacyData['privacy'] = (new privacy())->_get($_uid);
                $alertData['alerts'] = array('message' => 'All changes are saved', 'type' => 'success');
            }
        $app->response->setJsonContent(array_merge($authData, $profileData, $privacyData, $alertData));
        $app->response->send();
        //return json_encode(array_merge($authData, $profileData, $privacyData), JSON_PRETTY_PRINT);

        //save all data
    });
echo $app->handle();