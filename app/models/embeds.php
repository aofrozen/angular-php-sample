<?php


namespace app\models;

use MongoDate,
    MongoId;

class embeds extends \Phalcon\Mvc\Collection
    {
    /* You can't replace _ with capital letter due to embed.ly API uses _ */
        public $url;
        public $type;
        public $provider_name;
        public $provider_url;
        public $title;
        public $width;
        public $height;
        public $html;
        public $thumbnail_url;
        public $thumbnail_width;
        public $thumbnail_height;
        public $version;

        public function _create($source)
            {
                if($source->version == '1.0') //good to go
                    {
                        $mongo = $this->getDI()->getShared('mongo');
                        $embeds = new \stdClass();
                        $embeds->ts = new MongoDate();
                        $embeds->url = $source->url;
                        $embeds->type = $source->type;
                        $embeds->provider_name = $source->provider_name;
                        $embeds->provider_url = $source->url;
                        $embeds->title = $source->title;
                        $embeds->description = $source->description;
                        $embeds->version = $source->version;
                        $embeds->thumbnail_url = $source->thumbnail_url;
                        $embeds->thumbnail_width = $source->thumbnail_width;
                        $embeds->thumbnail_height = $source->thumbnail_height;
                        if($embeds->type == 'video')
                        $embeds->html = $source->html;
                        if($mongo->embeds->insert($embeds)['ok'] == 1)
                            return true;
                        return false;
                    }else{
                    return false;
                }
            }

        public function _delete()
            {
                $mongo = $this->getDI()->getShared('mongo');

            }

        public function _get($url)
            {
                $mongo = $this->getDI()->getShared('mongo');
                $result = iterator_to_array($mongo->embeds->find(array('url' => (string)$url))->limit(1), false);
                if(count($result) > 0)
                    return $result[0];
                return false;
            }

        public function _set()
            {
                $mongo = $this->getDI()->getShared('mongo');
            }
    }