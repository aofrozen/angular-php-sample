<?php

namespace app\library\angularstrap;

class alert
    {
        public function __contruct()
            {
                $this->data = new stdClass();
            }

        public function alert($type, $title, $content, $placement)
            {
                //$this->alert = 1;
                $this->data->type = $type;
                $this->data->title = $title;
                $this->data->content = $content;
                $this->data->placement = $placement;
                return $this->data;
            }
    }