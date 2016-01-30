<?php

namespace app\library\file;

/*
image->upload(array(files));
*/
use app\library\fs\fileStorage,
    Phalcon\Di\Injectable,
    Phalcon\Image\Adapter\GD;

class image extends Injectable
    {
        private function getDir()
            {
                return dirname(dirname(dirname(__DIR__)));
            }
        public function rotateProfileAvatar($avatarData)
        {
            $manger = new \Intervention\Image\ImageManager();
            $image = $manger->make($avatarData['fileLocation'])->rotate(90);
            $image->save($avatarData['fileLocation'], 80);
        }
        public function uploadProfileAvatar($avatarData)
            {
                $files = $this->getFiles();
                $fileLink = $this->getDir() . '/temp/' . rand(0, 10000) . time() . md5($files[0]->getName()).'.jpg';
                $files[0]->moveTo($fileLink);
                /* Optimize */
                $factory = new \ImageOptimizer\OptimizerFactory();
                $optimizer = $factory->get('jpegoptim');
                $filepath = $fileLink;
                $optimizer->optimize($filepath);
                /* End Optimize */
                $manger = new \Intervention\Image\ImageManager();
                $image = $manger->make($fileLink);
                $this->orientate($image, $image->exif('Orientation'));
                $image = $this->convertToThumbnail($image);
                $image->save($fileLink, 80);
                if (!empty($avatarData))
                    {
                        $locationData = (new fileStorage())->upload($fileLink, null, $avatarData);
                    } else
                    {
                        $locationData = (new fileStorage())->upload($fileLink);
                    }
                return $locationData;
            }
    public function rotateProfileWall($homeWallData)
    {
        print_r($homeWallData);
        $manger = new \Intervention\Image\ImageManager();
        $image = $manger->make($homeWallData['fileLocation'])->rotate(90);
        $image->save($homeWallData['fileLocation'], 80);
    }
        public function uploadProfileWall($wallData)
            {
                $files = $this->getFiles();
                if (count($files) < 1)
                    return false;
                $fileLink = $this->getDir() . '/temp/' . rand(0, 10000) . time() . md5($files[0]->getName()).'.jpg';
                $files[0]->moveTo($fileLink);
                /* Optimize */
                $factory = new \ImageOptimizer\OptimizerFactory();
                $optimizer = $factory->get('jpegoptim');
                $filepath = $fileLink;
                $optimizer->optimize($filepath);
                /* End Optimize */
                $manger = new \Intervention\Image\ImageManager();
                $image = $manger->make($fileLink);
                $this->orientate($image, $image->exif('Orientation'));
                $image = $this->resize($image, '1920', '1080');
                $image->save($fileLink, 80);
                if (!empty($wallData))
                    {
                        $locationData = (new fileStorage())->upload($fileLink, null, $wallData);
                    } else
                    {
                        $locationData = (new fileStorage())->upload($fileLink);
                    }
                return $locationData;
            }
    public function rotateHomeWall($homeWallData)
    {
        $manger = new \Intervention\Image\ImageManager();
        $image = $manger->make($homeWallData['fileLocation'])->rotate(90);
        $image->save($homeWallData['fileLocation'], 80);
    }
    public function uploadHomeWall($wallData)
    {
        $files = $this->getFiles();
        if (count($files) < 1)
            return false;
        $fileLink = $this->getDir() . '/temp/' . rand(0, 10000) . time() . md5($files[0]->getName()).'.jpg';
        $files[0]->moveTo($fileLink);
        /* Optimize */
        $factory = new \ImageOptimizer\OptimizerFactory();
        $optimizer = $factory->get('jpegoptim');
        $filepath = $fileLink;
        $optimizer->optimize($filepath);
        /* End Optimize */
        $manger = new \Intervention\Image\ImageManager();
        $image = $manger->make($fileLink);
        $this->orientate($image, $image->exif('Orientation'));
        $image = $this->resize($image, '1920', '1080');
        $image->save($fileLink, 80);
        if (!empty($wallData))
        {
            $locationData = (new fileStorage())->upload($fileLink, null, $wallData);
        } else
        {
            $locationData = (new fileStorage())->upload($fileLink);
        }
        return $locationData;
    }
    public function rotatePhoto($photoData)
    {
        $manger = new \Intervention\Image\ImageManager();
        $image = $manger->make($photoData->fileLocation)->rotate(90);
        $image->save($photoData->fileLocation, 80);
    }
    public function uploadPhoto()
    {
        $files = $this->getFiles();
        if (count($files) < 1)
            return false;
        $fileLink = $this->getDir() . '/temp/' . rand(0, 10000) . time() . md5($files[0]->getName()).'.jpg';
        $thumbFileLink = $this->getDir() . '/temp/small_' . rand(0, 10000) . time() . md5($files[0]->getName()).'.jpg';
        $files[0]->moveTo($fileLink);
        /* Optimize */
        $factory = new \ImageOptimizer\OptimizerFactory();
        $optimizer = $factory->get('jpegoptim');
        $filepath = $fileLink;
        $optimizer->optimize($filepath);
        /* End Optimize */
        $manger = new \Intervention\Image\ImageManager();
        $image = $manger->make($fileLink);
        //iphone and android
        if(($image->mime === 'image/jpg' || $image->mime === 'image/jpeg') && $image->exif('Orientation'))
        $this->orientate($image, $image->exif('Orientation'));
        $imageWidth = $image->getWidth();
        $imageHeight = $image->getHeight();
        $imageSize = $image->filesize();
        //$image = $this->resize($image, '1920', '1080');
        $image->save($fileLink, 80);
        $image = $this->convertToThumbnail($image);
        $image->save($thumbFileLink, 80);
        /* End of Thumbnail */

        $locationData = (new fileStorage())->upload($fileLink, null, false, true);
        $thumbLocationData = (new fileStorage())->upload($thumbFileLink, null, false, true, 'small_'.$locationData->fileID);
        $locationData->thumb = $thumbLocationData;
        $locationData->width = $imageWidth;
        $locationData->height = $imageHeight;
        $locationData->size = $imageSize;
        return $locationData;
    }

    public function uploadPhotoAlbum()
    {
        $files = $this->getFiles();
        if (count($files) < 1)
            return false;
        $fileLink = $this->getDir() . '/temp/' . rand(0, 10000) . time() . md5($files[0]->getName()).'.jpg';
        $files[0]->moveTo($fileLink);
        /* Optimize */
        $factory = new \ImageOptimizer\OptimizerFactory();
        $optimizer = $factory->get('jpegoptim');
        $filepath = $fileLink;
        $optimizer->optimize($filepath);
        /* End Optimize */
        $manger = new \Intervention\Image\ImageManager();
        $image = $manger->make($fileLink);
        //iphone and android
        if(($image->mime === 'image/jpg' || $image->mime === 'image/jpeg') && $image->exif('Orientation'))
            $this->orientate($image, $image->exif('Orientation'));
        $image = $this->convertToThumbnail($image);
        $image->save($fileLink, 80);
        $locationData = (new fileStorage())->upload($fileLink);
        return $locationData;
    }

    private function resize($image, $maxWidth, $maxHeight)
    {
        $imageWidth = $image->getWidth();
        $imageHeight = $image->getHeight();
        if($imageWidth < $maxWidth && $imageHeight < $maxHeight)
            return $image;
        if($imageWidth > $imageHeight)
        {
            $ratio = ($maxHeight/$imageHeight);
            $newWidth = $ratio*$imageWidth;
            $image = $image->resize($newWidth, $maxHeight);
        }else{
            $ratio = ($maxWidth/$imageWidth);
            $newHeight = $ratio*$imageHeight;
            $image = $image->resize($maxWidth, $newHeight);
        }
        return $image;
    }
        private function convertToThumbnail($image)
            {
                $imageWidth = $image->getWidth();
                $imageHeight = $image->getHeight();
                /* Thumbnail */
                $maxHeight = 200;
                $maxWidth = 200;
                if($imageWidth > $imageHeight)
                    {
                        $ratio = ($maxHeight/$imageHeight);
                        $newWidth = $ratio*$imageWidth;
                        $xCenter = round($newWidth/2)-round($maxWidth/2);
                        $yCenter = round($maxHeight/2)-round($maxHeight/2);
                        $image = $image->resize($newWidth, $maxHeight)->crop($maxWidth, $maxHeight, $xCenter, $yCenter);
                    }else{
                    $ratio = ($maxWidth/$imageWidth);
                    $newHeight = $ratio*$imageHeight;
                    $xCenter = round($maxWidth/2)-round($maxWidth/2);
                    $yCenter = round($newHeight/2)-round($maxHeight/2);
                    $image = $image->resize($maxWidth, $newHeight)->crop($maxWidth, $maxHeight, $xCenter, $yCenter);
                }
                return $image;
            }

        private function getFiles()
            {
                $files = array();
                if ($this->request->hasFiles() == true)
                    {
                        foreach ($this->request->getUploadedFiles() as $file)
                            {
                                // get uploader service or \Uploader\Uploader
                                $uploader = $this->di->get('uploader');
                                // setting up uloader rules
                                $uploader->setRules([
                                    'minsize' => 1000,   // bytes
                                    'maxsize' => 4000000,// bytes
                                    'mimes' => [       // any allowed mime types
                                        'image/gif',
                                        'image/jpeg',
                                        'image/png',
                                    ],
                                    'extensions' => [  // any allowed extensions
                                        'gif',
                                        'jpeg',
                                        'jpg',
                                        'png'
                                    ],
                                    'sanitize' => true,
                                    'hash' => 'md5'
                                ]);
                                if ($uploader->isValid() === true)
                                    {
                                        array_push($files, $file);
                                    } else
                                    {
                                        $alerts = array('alerts' => array('title', 'Image Upload Error', 'message' => $uploader->getErrors()[0], 'type' => 'danger'));
                                        $this->response->setJsonContent($alerts);
                                        $this->response->send();
                                        exit;
                                    }
                            }
                        return $files;
                    } else
                    {
                        echo 'no files';
                        return null;
                    }
            }

        public function orientate($image, $orientation)
        {
            switch($orientation){
                case 1:
                    return $image;
                case 2:
                    return $image->flip('h');
                case 3:
                    return $image->rotate(180);
                case 4:
                    return $image->rotate(180)->flip('h');
                case 5:
                    return $image->rotate(-90)->flip('h');
                case 6:
                    return $image->rotate(-90);
                case 7:
                    return $image->rotate(-90)->flip('v');
                case 8:
                    return $image->rotate(90);
                default:
                    return $image;
            }
        }

        public function deleteAvatar($uid)
            {
            }

        public function deleteWall($uid)
            {
            }

        public function deletePostImage($pid, $index)
            {
            }
    }