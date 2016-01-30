<?php

namespace app\library\common;
class common
    {
        public function set($val, $else = null, $prefix = null, $suffix = null)
            {
                return (isset($val) ? $prefix.$val.$suffix : $else);
            }
        public function setOnlyHttp($url) {
            $disallowed = array('https://');
            foreach($disallowed as $d) {
                if(strpos($url, $d) === 0) {
                    return 'http://'.str_replace($d, '', $url);
                }
            }
            return $url;
        }
    }