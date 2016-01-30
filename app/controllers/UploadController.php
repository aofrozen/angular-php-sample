<?php

namespace app\controllers;

use Phalcon\Mvc\View,
    app\library\file\image,
    app\models\posts,
    app\models\photos,
    app\models\photoalbums,
    app\models\profiles,
    app\models\phototaggroups,
    app\library\session\userSessions;

class UploadController extends ControllerBase
    {
        public function imageAction($type)
            {
                $image = new image();
                $uid = (new userSessions)->getUserID();
                $data = new \stdClass();
                $data->profile = new \stdClass();
                $data->home = new \stdClass();
                $data->photos = new \stdClass();
                $data->photoAlbums = new \stdClass();
                $albumId = $this->request->getPost('albumId');
                $tags = $this->request->getPost('tags'); //require to convert tags into array
                $tags = explode(',', $tags);
                switch ($type)
                    {
                    case 'profileWall': //profile wall
                            $result = json_decode(json_encode(profiles::findFirst(array(array('uid' => $uid)))));
                            if (!$result)
                                return false;
                            $fileData = $image->uploadProfileWall($result->profileWall);
                            if ($fileData)
                                (new profiles())->setProfileWall($uid, $fileData);
                            $data->profile->wall = 'http://'.$fileData->webFileLocation.'?'.time();
                            $data->profile->uid = $uid;
                            $this->response->setJsonContent($data);
                            $this->response->send();
                            break;
                    case 'profileAvatar': //profile avatar
                            $result = json_decode(json_encode(profiles::findFirst(array(array('uid' => $uid)))));
                            if (!$result)
                                return false;
                            $fileData = $image->uploadProfileAvatar($result->profileAvatar);
                            if ($fileData)
                                (new profiles())->setProfileAvatar($uid, $fileData);
                            $data->profile->avatar = 'http://'.$fileData->webFileLocation.'?'.time();
                            $data->profile->uid = $uid;
                            $this->response->setJsonContent($data);
                            $this->response->send();
                            break;
                    case 'homeWall':
                            $result = json_decode(json_encode(profiles::findFirst(array(array('uid' => $uid)))));
                            if (!$result)
                                return false;
                            $fileData = $image->uploadHomeWall($result->homeWall);
                            if ($fileData)
                                (new profiles())->setHomeWall($uid, $fileData);
                            $data->home->wall = 'http://'.$fileData->webFileLocation.'?'.time();
                            $data->home->uid = $uid;
                            $this->response->setJsonContent($data);
                            $this->response->send();
                            break;
                    case 'photo':
                            $result = json_decode(json_encode(profiles::findFirst(array(array('uid' => $uid)))));
                            if (!$result)
                                return false;
                            //add photo to photos
                            $fileData = $image->uploadPhoto();
                            if ($fileData)
                                {
                                    $photoItem = (new photos())->_create($uid, $albumId, $fileData, null, null, $tags);
                                    (new phototaggroups())->_save($uid, $tags);
                                }

                                //(new profiles())->setHomeWall($uid, $fileData);
                            $data->photos->items = array($photoItem);
                            $data->photos->uid = $uid;
                            $this->response->setJsonContent($data);
                            $this->response->send();
                            break;
                    case 'photoAlbum':
                            $result = json_decode(json_encode(profiles::findFirst(array(array('uid' => $uid)))));
                            if (!$result)
                                return false;
                            //add photo to album photos
                            $fileData = $image->uploadPhotoAlbum();
                            if ($fileData)
                            {
                                $photoAlbumData = (new photoalbums())->_create($uid, $fileData, null, null);
                            }
                            $data->photoAlbums->photo = 'http://'.$fileData->webFileLocation.'?'.time();
                            $data->photoAlbums->uid = $uid;
                            $this->response->setJsonContent($data);
                            $this->response->send();
                        break;
                    default:
                            echo 'type not found';
                            break;
                    }
                $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            }
    }