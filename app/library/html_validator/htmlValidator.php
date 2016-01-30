<?php

namespace app\library\html_validator;

use HTMLPurifier,
    HTMLPurifier_Config;

class htmlValidator
    {
        public function check($dirtyArrayData, $type)
            {
                /*
                $config->set('HTML.Allowed', 'p,b,a[href],i');
                $config->set('URI.Base', 'http://www.example.com');
                $config->set('URI.MakeAbsolute', true);
                $config->set('AutoFormat.AutoParagraph', true);
                */
                switch ($type)
                    {
                    case 'list':
                            return $this->validiateList($dirtyArrayData);
                            break;
                    case 'vote':
                            return $this->validiateVote($dirtyArrayData);
                            break;
                    case 'article':
                            return $this->validateArticle($dirtyArrayData);
                            break;
                    default:
                            return false;
                            break;
                    }
            }

        public function validiateList($dirtyArrayData)
            {
                $config = HTMLPurifier_Config::createDefault();
                $config->set('CSS.AllowedProperties', '');
                $purifier = new HTMLPurifier($config);
                $dirtyArrayData['title'] = $purifier->purify($dirtyArrayData['title']);
                $dirtyArrayData['description'] = $purifier->purify($dirtyArrayData['description']);
                $dirtyItemsArrayLen = count($dirtyArrayData['items']);
                for ($x = 0; $x < $dirtyItemsArrayLen; $x++)
                    {
                        $dirtyArrayData['items'][$x]['title'] = $purifier->purify($dirtyArrayData['items'][$x]['title']);
                        $dirtyArrayData['items'][$x]['description'] = $purifier->purify($dirtyArrayData['items'][$x]['description']);
                    }
                return $dirtyArrayData;
            }

        public function validiateVote($dirtyArrayData)
            {
                return $dirtyArrayData;
            }

        public function validiateArticle($dirtyArrayData)
            {
                return $dirtyArrayData;
            }
    }