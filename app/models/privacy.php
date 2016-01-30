<?php

namespace app\models;

class privacy extends \Phalcon\Mvc\Collection
    {
        public $uid;
        public $privacy;

    /*
     * Data
     *
     *
     * 1 = Me
     * 2 = Only friend
     * 3 = All
     *
     *
     * Profile
     *  read_profile_filter
     *
     *
     * Contact
     *  read_email_filter
     *  read_phone_filter
     *  message_filter
     *
     */
        public function getSource()
            {
                return 'privacy';
            }

        public function build($uid)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $privacy = new \stdClass();
                $privacy->privacy = new \stdClass();
                $privacy->privacy->profile = new \stdClass();
                $privacy->privacy->contact = new \stdClass();
                $privacy->uid = $uid;
                $privacy->privacy->profile->read_profile_filter = 2;
                $privacy->privacy->contact->read_email_filter = 1;
                $privacy->privacy->contact->read_phone_filter = 1;
                $privacy->privacy->contact->message_filter = 2;
                if ($mongo->privacy->insert($privacy)['ok'] == 1)
                    return true;
                return false;
            }

        public function _set($uid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $result = $mongo->privacy->update(array('uid' => $uid), array('$set' => $data));
                if ($result)
                    return true;
                return false;
            }

        public function _get($uid)
            {
                return privacy::findFirst(array(array('uid' => $uid)));
            }
    }