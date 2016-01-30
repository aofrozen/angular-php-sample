<?php

namespace app\models;

use MongoDate,
    MongoId;

class journals extends \Phalcon\Mvc\Collection
    {
    /*
     * Journal Type
     *
     * 1. Short Journal
     *  a. youtube with a short description
     *  b. pictures with a short description
     *  c. short description (ex: talk)
     *
     * 2. Long Journal
     *  a. list with a long description
     *  b. article with a long description
     *
     */
        public $ts;
        public $uid;
        public $data;

        public function getSource()
            {
                return 'journals';
            }

        public function beforeCreate()
            {
                $this->ts = new MongoDate();
            }

        public function _create($uid, $data)
            {
                $journal = new journals();
                $journal->uid = $uid;
                $journal->data = $data;
                if ($journal->save())
                    return true;
                return false;
            }

        public function _delete($uid, $jid)
            {
                $journal = $this->findFirst(array(array('uid' => $uid, '_id' => new MongoId($jid))));
                $journal->delete();
            }

        public function _update($uid, $jid, $data)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $mongo->journals->update(array('uid' => $uid, '_id' => new MongoId($jid)), array()); //data is json data
            }
    }