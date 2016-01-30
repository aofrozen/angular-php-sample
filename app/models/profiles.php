<?php

namespace app\models;

use \app\library\file\image;

class profiles extends \Phalcon\Mvc\Collection
    {
        public $uid;
        public $username;
        public $sex;
        public $name;
        public $fName;
        public $lName;
        public $status;
        public $avatar;
        public $wall;
        public $links;
        public $height;
        public $race;
        public $relationship;
        public $handicap;
        public $birthday;
        public $country;
        public $state;
        public $city;
        public $mobileNumbers;
        public $description;

        public $profiles;

        public function build($uid, $profileData)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $profile = new \stdClass();
                $profile->links = new \stdClass();
                $profile->uid = $uid;
                $profile->key = (string)$this->generateUniqueKey($uid);
                $profile->username = '';
                $profile->fName = $profileData['fName'];
                $profile->lName = $profileData['lName'];
                $profile->profileWall = '';
                $profile->homeWall = '';
                $profile->mobileNumbers = '';
                $profile->status = '';
                $profile->profileAvatar = '';
                $profile->sex = $profileData['sex'];
                $profile->birthday = $profileData['birthday'];
                $profile->race = '';
                $profile->relationship = '';
                $profile->handicap = '';
                $profile->height = '';
                $profile->description = '';
                $profile->links->facebook = ($profileData['FacebookLink'] ? $profileData['FacebookLink'] : '');
                $profile->links->google = ($profileData['GoogleLink'] ? $profileData['GoogleLink'] : '');
                $profile->links->twitter = ($profileData['TwitterLink'] ? $profileData['TwitterLink'] : '');
                if ($mongo->profiles->insert($profile)['ok'] == 1)
                    return true;
                return false;
            }

        public function generateUniqueKey($uid)
            {
                $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $segment_chars = 5;
                $num_segments = 5;
                $key_string = '';

                for ($i = 0; $i < $num_segments; $i++)
                    {

                        $segment = '';

                        for ($j = 0; $j < $segment_chars; $j++)
                            {
                                $segment .= $tokens[rand(0, 35)];
                            }

                        $key_string .= $segment;

                        if ($i < ($num_segments - 1))
                            {
                                $key_string .= '-';
                            }
                    }
                $hashids = $this->getDI()->getShared('hashids');

                return 'U'.$hashids->encode($uid).'-'.$key_string;
            }

        public function getSource()
            {
                return 'profiles';
            }

        public function setProfileWall($uid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->profiles->update(array('uid' => (int)$uid), array('$set' => array('profileWall' => $data)));
            }

        public function setProfileStyles($uid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                if ($mongo->profiles->update(array('uid' => $uid), array('$set' => array('profileWall.position' => $data->wallPosition))))
                    return true;
                return false;
            }

        public function rotateHomeWall($uid)
            {
                $homeData = $this->_get($uid);
                (new image())->rotateHomeWall($homeData['homeWall']);
            }

        public function rotateProfileWall($uid)
            {
                $profileData = $this->_get($uid);
                (new image())->rotateProfileWall($profileData['profileWall']);
            }

        public function setHomeWall($uid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->profiles->update(array('uid' => (int)$uid), array('$set' => array('homeWall' => $data)));
            }

        public function setHomeStyles($uid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                if ($mongo->profiles->update(array('uid' => (int)$uid, array('$set' => array('homeWall.position' => $data->wallPosition)))))
                    return true;
                return false;
            }

        public function setProfileAvatar($uid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->profiles->update(array('uid' => (int)$uid), array('$set' => array('profileAvatar' => $data)));
            }

        public function _get($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $profile = iterator_to_array($mongo->profiles->find(array('uid' => (int)$uid), array('_id' => 0)), false);
                if (!$profile) return array();
                return $profile[0]; //return array
            }

        public function _set($uid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $profile = $mongo->profiles->update(array('uid' => (int)$uid), array('$set' => $data));
                if (!$profile) return array();
                return $profile;
            }

        public function _getKey($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $profile = iterator_to_array($mongo->profiles->find(array('uid' => (int)$uid), array('key' => 1)), false);
                if (!$profile) return array();
                return $profile[0]['key']; //return array
            }

        public function usernameExists($username, $uid = null)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $profile = iterator_to_array($mongo->profiles->find(array('username' => $username))->limit(1), false);
                if (count($profile) == 0)
                    return false;
                if ($profile[0]['uid'] == $uid)
                    return false;
                return true;
            }

        public function convertUsernameToPID($username)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $profile = iterator_to_array($mongo->profiles->find(array('username' => (string)$username), array('uid' => 1)), false)[0];
                if (!$profile) return array();
                return $profile;
            }

        public function _getProfilesWithUserIds($userIds)
            {
                $usersListA = array();
                $mongo = $this->getDI()->getShared('mongo');
                $users = iterator_to_array($mongo->profiles->find(array('uid' => array('$in' => $userIds))), false);
                $total = count($users);
                for ($x = 0; $x < $total; $x++)
                    {
                        $usersListA[$users[$x]['uid']] = $users[$x];
                    }
                return $usersListA;
            }

        public function _mergeProfilesWithUserIds($source)
            {
                $userIds = $this->_getUserIdsFromData($source);
                $profiles = $this->_getProfilesWithUserIds($userIds);
                $this->_saveProfiles($profiles);
                return $this->_addProfilesToData($source, $profiles);
            }

        public function _saveProfiles($source)
            {
                $this->profiles = $source;
            }

        public function _getProfiles()
            {
                return $this->profiles;
            }

        public function _mergeProfilesWithUserIdsCache($source)
            {
                return $this->_addProfilesToData($source, $this->_getProfiles());
            }

        public function _addProfilesToData($source, $profiles)
            {
                $newSource = $source;
                $total = count($source);
                for ($x = 0; $x < $total; $x++)
                    {
                        if (array_key_exists($x, $source))
                            {
                                if (is_array($source[$x]))
                                    {
                                        foreach ($source[$x] as $key => $val)
                                            {
                                                if (is_array($val))
                                                    {
                                                        $newSource[$x][$key] = $this->_addProfilesToData($val, $profiles);
                                                    } elseif ($key == 'uid' || $key == 'fid' || $key == 'followUid')
                                                    {
                                                        if (is_array($newSource[$x]))
                                                            {
                                                                $newSource[$x][$key] = array();
                                                                $newSource[$x][$key][$key] = $val;
                                                                $newSource[$x][$key]['name'] = (empty($profiles[$val]['fName']) ? null : $profiles[$val]['fName']) . ' ' . (empty($profiles[$val]['lName']) ? null : $profiles[$val]['lName']);
                                                                $newSource[$x][$key]['avatar'] = (empty($profiles[$val]['profileAvatar']['webFileLocation']) ? null : 'http://' . $profiles[$val]['profileAvatar']['webFileLocation'] . '?' . time());
                                                                $newSource[$x][$key]['username'] = (empty($profiles[$val]['username']) ? null : $profiles[$val]['username']);
                                                                $newSource[$x][$key]['wall'] = (empty($profiles[$val]['profileWall']['webFileLocation']) ? null : 'http://' . $profiles[$val]['profileWall']['webFileLocation'] . '?' . time());
                                                            }
                                                    }
                                            }
                                    }
                            }
                    }

                return $newSource;
            }

        public function _getUserIdsFromData($source, $usersListA = null)
            {
                if (!is_array($usersListA))
                    $usersListA = array();
                $total = count($source);
                for ($x = 0; $x < $total; $x++)
                    {
                        if (array_key_exists($x, $source))
                            {
                                if (is_array($source[$x]))
                                    {
                                        foreach ($source[$x] as $key => $val)
                                            {
                                                if (gettype($val) === 'array')
                                                    {
                                                        $usersListA = $this->_getUserIdsFromData($val, $usersListA);
                                                    } elseif ($key == 'uid' || $key == 'fid')
                                                    {
                                                        if (!in_array($val, $usersListA))
                                                            array_push($usersListA, (int)$val);
                                                    }
                                            }
                                    }
                            }
                    }
                return $usersListA;
            }
    }