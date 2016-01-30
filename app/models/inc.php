<?php

namespace app\models;
class inc extends \Phalcon\Mvc\Collection
    {
        public function getSource()
            {
                return 'inc';
            }

        public function _execute($field_id)
            {
                $mongo = $this->getDI()->getShared('mongo');
                if ($mongo->inc->update(array('field_id' => $field_id), array('$inc' => array('next_id' => 1))))
                    {
                        return iterator_to_array($mongo->inc->find(array('field_id' => $field_id)), false)[0];
                    } else
                    {
                        return false;
                    }

            }
    }