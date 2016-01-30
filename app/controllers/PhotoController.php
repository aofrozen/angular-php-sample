<?php

namespace app\controllers;

use \Phalcon\Mvc\View;

class PhotoController extends ControllerBase
    {
        public function initialize()
            {
                parent::initialize();
            }

        public function fireAction($pid)
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pid = $pid;
                $this->view->pick('profile/photos/photos');
            }

        public function albumsAction()
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/photos/photoAlbums');
            }

        public function allPhotosAction()
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/photos/allPhotos');
            }

        public function photoItemModalAction()
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/photos/photoItemModal');
            }

        public function photoPlusPopoverAction()
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/photos/photoPlusPopover');
            }

        public function editPhotoItemModalAction()
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/photos/editPhotoItemModal');
            }

        public function photoUploadModalAction()
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/photos/photoUploadModal');
            }

        public function photoAlbumUploadModalAction()
            {
                $this->view->setRenderLevel(View::LEVEL_LAYOUT);
                $this->view->pick('profile/photos/photoAlbumUploadModal');
            }

    }