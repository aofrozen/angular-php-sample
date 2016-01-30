
angular.module('socialSample.profilePhoto', [])
    .controller('profilePhotoController', ['$scope', 'profilePhotoService', '$state', function ($scope, profilePhotoS) {
        var albumId,
            albumIdChange,
            loadPhotos;
        $scope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {
            if (typeof toParams.id !== 'undefined' && toState.name === 'profile.photos.albumPhotos') {
                albumIdChange = albumId != toParams.id;
                albumId = toParams.id;
            }
            if (typeof albumId !== 'undefined' && toState.name === 'profile.photos.albumPhotos') {
                //if (albumIdChange) {
                    $scope.mc.photos = null;
                    profilePhotoS._loadAlbumPhotos($scope.pid, albumId, function (response) {
                        profilePhotoS._activeAlbumPhotos();
                        $scope.mc.photos = response;
                    });
                //}
            } else if (toState.name === 'profile.photos') {
                loadPhotos = typeof $scope.mc.photos === 'undefined' || $scope.mc.photos.uid != $scope.mc.profile.uid || fromState.name != toState.name;
                //if (loadPhotos && fromState.name !== 'profilePhotoItem') {
                    $scope.mc.photos = null;
                    profilePhotoS._loadPhotos($scope.pid, function (response) {
                        profilePhotoS._activePhotos();
                        $scope.mc.photos = response;
                    });
                //}
            }
        });
    }])
    .controller('profilePhotoAlbumsController', ['$scope', 'ajaxService', 'profilePhotoService', 'profileService', function ($scope, $http, profilePhotoS, profileService) {
        $scope.mc.profile = profileService._get();
        var loadPhotoAlbums = typeof $scope.mc.photoAlbums === 'undefined';
        if (loadPhotoAlbums) {
            profilePhotoS._loadPhotoAlbums($scope.pid, function (response) {
                $scope.mc.photoAlbums = response;
            });
        }
    }])
    .controller('profilePhotoAlbumController', ['$scope', function ($scope) {
        console.info('photo album controller');
    }])
    .controller('photoUploadModalController', ['$scope', 'FileUploader', 'profilePhotoService', 'profileService', '$alert', '$stateParams', function($scope, FileUploader, profilePhotoS, profileService, $alert, $stateParams){

        /* Album Id */

        var albumId;
        console.info(profilePhotoS._getPhotos());
        if (typeof $stateParams.id !== 'undefined') {
            albumId = $stateParams.id;
        } else {
            albumId = null;
        }

        /* Tags */
        $scope.loadTags = function(query) {
           // return $http.get('/tags?query=' + query);
        };


        /* Uploader */
        var uploader = $scope.uploader = new FileUploader({
            url: '/upload/image/photo'
        });
        // FILTERS

        uploader.filters.push({
            name: 'imageFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        });

        // CALLBACKS

        uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
            console.info('onWhenAddingFileFailed', item, filter, options);
        };
        uploader.onAfterAddingFile = function(fileItem) {
            console.info('onAfterAddingFile', fileItem);
        };
        uploader.onAfterAddingAll = function(addedFileItems) {
            console.info('onAfterAddingAll', addedFileItems);
        };
        uploader.onBeforeUploadItem = function(item) {
            var tags = [];
            if(typeof $scope.tags !== 'undefined')
            {
                $scope.tags.forEach(function(tag){
                    tags.push(tag.text);
                });
                item.formData[0] = {"tags": tags.join()};
            }
        };
        uploader.onProgressItem = function(fileItem, progress) {
            console.info('onProgressItem', fileItem, progress);
        };
        uploader.onProgressAll = function(progress) {
            console.info('onProgressAll', progress);
        };
        uploader.onSuccessItem = function(fileItem, response, status, headers) {
            console.info('onSuccessItem', fileItem, response, status, headers);
        };
        uploader.onErrorItem = function(fileItem, response, status, headers) {
            console.info('onErrorItem', fileItem, response, status, headers);
        };
        uploader.onCancelItem = function(fileItem, response, status, headers) {
            console.info('onCancelItem', fileItem, response, status, headers);
        };
        uploader.onCompleteItem = function(fileItem, response, status, headers) {
            console.info('onCompleteItem', fileItem, response, status, headers);
            var profile = profileService._get();
            if (typeof response.photos !== 'undefined') {
                if (response.photos.uid == profile.uid) {
                    profilePhotoS._pushPhotoItem(response.photos.items[0], function () {
                        //$scope.mc.photos = profilePhotoS._getPhotos();
                    });
                }
            }
            if (typeof response.alerts !== "undefined") {
                $alert({
                    title: response.alerts.title,
                    content: response.alerts.message,
                    type: response.alerts.type,
                    show: true,
                    placement: 'top',
                    duration: 5
                });
            }
        };
        uploader.onCompleteAll = function() {
            console.info('onCompleteAll');
        };
        console.info('uploader', uploader);
    }])
    .controller('photoPlusPopoverController', ['$scope', 'ngProgress', 'FileUploader', 'profilePhotoService', '$stateParams', '$alert', function ($scope, ngProgress, FileUploader, profilePhotoS, $stateParams, $alert) {
        var albumId;
        console.info(profilePhotoS._getPhotos());
        if (typeof $stateParams.id !== 'undefined') {
            albumId = $stateParams.id;
        } else {
            albumId = null;
        }
        var photoAlbumUploader = $scope.photoAlbumUploader = new FileUploader({
            url: '/upload/image/photoAlbum'
        });
        var photoUploader = $scope.photoUploader = new FileUploader({
            url: '/upload/image/photo',
            formData: [{"albumId": albumId}]
        });
        photoUploader.onAfterAddingFile = function (fileItem) {
            fileItem.upload();
        };
        photoUploader.onCompleteItem = function (fileItem, response, status, headers) {
            fileItem.remove();
            ngProgress.complete();
            if (typeof response.photos !== 'undefined') {
                if (response.photos.uid == $scope.mc.profile.uid) {
                    profilePhotoS._pushPhotoItem(response.photos.items[0], function () {
                        $scope.mc.photos = profilePhotoS._getPhotos();
                    });
                }
            }
            if (typeof response.alerts !== "undefined") {
                $alert({
                    title: response.alerts.title,
                    content: response.alerts.message,
                    type: response.alerts.type,
                    show: true,
                    placement: 'top',
                    duration: 5
                });
            }
        };
        photoUploader.onProgressItem = function (fileItem, progress) {
            ngProgress.set(progress);
        };

        photoAlbumUploader.onAfterAddingFile = function (fileItem) {
            fileItem.upload();
        };
        photoAlbumUploader.onCompleteItem = function (fileItem, response, status, headers) {
            fileItem.remove();
            ngProgress.complete();
            if (typeof response.photoAlbums !== 'undefined') {
                if (response.photoAlbums.uid == $scope.mc.profile.uid) {
                    profilePhotoS._loadPhotoAlbums($scope.pid, function (data) {
                        $scope.mc.photoAlbums = data;
                    });
                }
            }
            if (typeof response.alerts !== "undefined")
                $alert({
                    title: response.alerts.title,
                    content: response.alerts.message,
                    type: response.alerts.type,
                    show: true,
                    placement: 'top',
                    duration: 5
                });
        };
        photoAlbumUploader.onProgressItem = function (fileItem, progress) {
            ngProgress.set(progress);
        };
    }]);