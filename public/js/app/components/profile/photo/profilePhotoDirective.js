angular.module('socialSample.profilePhotoDirective', [])

    /* Photo Thumbs */
    .directive('ngThumb', ['$window', function($window) {
        var helper = {
            support: !!($window.FileReader && $window.CanvasRenderingContext2D),
            isFile: function(item) {
                return angular.isObject(item) && item instanceof $window.File;
            },
            isImage: function(file) {
                var type =  '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        };

        return {
            restrict: 'A',
            template: '<canvas/>',
            link: function(scope, element, attributes) {
                if (!helper.support) return;

                var params = scope.$eval(attributes.ngThumb);

                if (!helper.isFile(params.file)) return;
                if (!helper.isImage(params.file)) return;

                var canvas = element.find('canvas');
                var reader = new FileReader();

                reader.onload = onLoadFile;
                reader.readAsDataURL(params.file);

                function onLoadFile(event) {
                    var img = new Image();
                    img.onload = onLoadImage;
                    img.src = event.target.result;
                }

                function onLoadImage() {
                    var width = params.width || this.width / this.height * params.height;
                    var height = params.height || this.height / this.width * params.width;
                    canvas.attr({ width: width, height: height });
                    canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
                }
            }
        };
    }])
    /* Photo Options */

    .directive('allPhotosButton', [function () {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('click', function () {
                    console.info('all photos button');

                });
            }
        }
    }])
    .directive('uploadPhotoAlbumBtn', [function () {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('click', function () {
                    angular.element('#photoAlbumUploader').trigger('click');
                });
            }
        }
    }])
    .directive('photoAlbumUploadBtn', ['$modal', function ($modal) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('click', function () {
                    console.info('click before photo album uploader!');
                    /*
                     Start modal
                     */
                    scope.beforePhotoAlbumUploaderModal = $modal({
                        template: '/view/photos/photo-album-upload',
                        show: true,
                        backdropAnimation: 'modal-backdrop',
                        animation: '',
                        scope: scope
                    });
                });
            }
        }
    }])
    .directive('photoAlbumUploaderCtrl', [function () {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                console.info('before photo album uploader controller!');
                scope.$on('$destroy', function () {
                    console.info('destroyed before photo album uploader controller');
                    scope.beforePhotoAlbumUploaderModal.destroy();
                });
            }
        }
    }])
    .directive('photoPlusPopover', ['$popover', function ($popover) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                var popover = $popover(element, {
                    contentTemplate: '/view/photo-plus-popover',
                    html: true,
                    trigger: 'manual',
                    autoClose: true,
                    scope: scope,
                    placement: 'bottom',
                    animation: ''
                });
                element.bind('click', function () {
                    popover.show();
                });

            }
        }
    }])
    .directive('openPhotoAlbumUploadBtn', ['$modal', function ($modal) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('click', function (e) {
                    console.info('click before album uploader');

                    scope.beforePhotoUploaderModal = $modal({
                        template: '/view/photos/photo-album-upload',
                        show: true,
                        backdropAnimation: 'modal-backdrop',
                        animation: ''
                    });
                });
            }
        }
    }])
    .directive('openPhotoUploadBtn', ['$modal', function ($modal) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('click', function (e) {
                    console.info('click before photo uploader');
                    /*
                     Start modal
                     */
                    scope.beforePhotoUploaderModal = $modal({
                        template: '/view/photos/photo-upload',
                        show: true,
                        backdropAnimation: 'modal-backdrop',
                        animation: ''
                    });
                });
            }
        }
    }])
    .directive('uploadPhotoBtn', [function () {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('click', function () {
                    //angular.element('#photoUploader').trigger('click');
                });
            }
        }
    }])


    /* Photo Item */
    .directive('profilePhotoItemCtrl', [function () {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                console.info('photo-item-controller');
            }
        };

    }])
    .directive('deletePhotoCommentButton', ['profilePhotoService', function (profilePhotoS) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('click', function (e) {
                    console.info('delete photo comment');
                    var photoItem = profilePhotoS._getCurrentPhotoItem();
                    var commentId = attrs.commentId;
                    var commentIndex = attrs.commentIndex;
                    profilePhotoS._deleteComment(photoItem._id.$id, commentId, commentIndex, function () {

                    })
                });
            }
        }
    }])
    .directive('deletePhotoButton', ['profilePhotoService', '$modal', '$rootScope', function (profilePhotoS, $modal, $rootScope) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('click', function (e) {
                    console.info('clicked delete photo');
                    profilePhotoS._deletePhotoItem(function () {
                        $rootScope.photoItemModal.hide();
                    });
                })
            }
        }
    }])
    .directive('editPhotoButton', ['profilePhotoService', '$modal', function (profilePhotoS, $modal) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('click', function (e) {
                    console.info('clicked edit photo');
                    var photoItem = profilePhotoS._getCurrentPhotoItem();
                    scope.photoCaptionModel = photoItem.caption;
                    scope.tags = photoItem.tags;
                    console.info('tags');
                    console.info(scope.tags);
                    console.info(photoItem);
                    profilePhotoS.__editPhotoItemModal = $modal({
                        template: '/view/photos/item/edit',
                        show: true,
                        backdropAnimation: 'modal-backdrop',
                        animation: '',
                        scope: scope
                    });
                });
            }
        }
    }])
    .directive('editPhotoCtrl', ['profilePhotoService', function (profilePhotoS) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                profilePhotoS._setPhotoSliderDisabled(true);
            }
        }
    }])
    .directive('savePhotoChangeButton', ['profilePhotoService', 'ajaxService', '$alert', function (profilePhotoS, $http, $alert) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('click', function (e) {
                    console.info("photo caption: " + scope.photoCaptionModel);
                    console.info('saved');
                    var photoItem = profilePhotoS._getCurrentPhotoItem();
                    var tags = [];
                    var tagsModel = '';
                    if(typeof scope.tags !== 'undefined')
                    {
                        scope.tags.forEach(function(tag){
                            tags.push(tag.text);
                        });
                        tagsModel =  tags.join();
                    }
                    angular.extend(photoItem, {
                        'caption': scope.photoCaptionModel,
                        'tags' : tagsModel,
                        'privacy': {}
                    });
                    $http._ajax('put', '/ajax/photos/item', photoItem, function (response) {
                        response = response.data;
                        profilePhotoS._changePhotoItem(profilePhotoS.photoItemIndex, photoItem);
                        if (typeof response.alerts !== "undefined")
                            $alert({
                                content: response.alerts.message,
                                type: response.alerts.type,
                                show: true,
                                placement: 'top',
                                duration: 5
                            });
                    });
                });
            }
        }
    }])
    .directive('deletePhotoItem', ['profilePhotoService', function (profilePhotoS) {

    }])
    .directive('photoViewer', ['profilePhotoService', '$window', '$timeout', '$stateParams', 'userService', '$location', '$state', function (profilePhotoS, $window, $timeout, $stateParams, userService, $location, $state) {
        var yPosition, xPosition = 0;
        var maxWindowWidth = 800;

        function getClickPosition(e) {
            var parentPosition = getPosition(e.currentTarget);
            xPosition = e.clientX - parentPosition.x;
            yPosition = e.clientY - parentPosition.y;
        }

        function getPosition(element) {
            xPosition = 0;
            yPosition = 0;

            while (element) {
                xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
                yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
                element = element.offsetParent;
            }
            return {x: xPosition, y: yPosition};
        }

        function reload(photoItem, element) {
            $timeout(function () {
                setPhotoSize(photoItem, element);
            }, 100);
        }

        function setPhotoSize(photoItem, element) {
            var photoItemEl = angular.element(document.querySelector('#photo-item'));
            var photoDialogEl = angular.element(document.querySelector('#photo-dialog'));
            var windowHeight = $window.innerHeight - 150;
            var windowWidth = $window.innerWidth;
            var maxPhotoWidth = photoItem.photo.width * (element[0].clientHeight / photoItem.photo.height) - 10;
            console.info(photoItem.photo.width, photoItem.photo.height, windowHeight, windowWidth, maxPhotoWidth, element[0].clientWidth);
            console.info((photoItem.photo.width > photoItem.photo.height) || (photoItem.photo.height < windowHeight) || (windowWidth < maxWindowWidth) || (maxPhotoWidth > element[0].clientWidth));
            if ((photoItem.photo.width > photoItem.photo.height) || (photoItem.photo.height < windowHeight) || (windowWidth < maxWindowWidth) || (maxPhotoWidth > element[0].clientWidth)) {
                photoItemEl.attr('style', 'width: 100%; height: auto;');
                photoDialogEl.attr('style', '');
            } else {
                photoItemEl.attr('style', 'width: auto; height: 100%;');
                photoDialogEl.attr('style', 'height: 90%');
            }
        }

        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var w = angular.element($window);
                w.bind('resize', function () {
                    scope.$apply();
                });
                w.bind('keyup', function (e) {
                    var editPhotoItemModal = profilePhotoS.__editPhotoItemModal;
                    if(typeof editPhotoItemModal.$isShown !== 'undefined')
                    {
                        if(editPhotoItemModal.$isShown === true)
                        {
                            profilePhotoS._setPhotoSliderDisabled(true);
                        }else{
                            profilePhotoS._setPhotoSliderDisabled(false);
                        }
                    }
                        var isPhotoSliderDisabled = profilePhotoS._isPhotoSliderDisabled();
                        if (e.which == 39 && isPhotoSliderDisabled !== true) {
                            scope.$apply(function () {
                                scope.mc.photoItem = profilePhotoS._nextPhotoItem();
                                setPhotoSize(scope.mc.photoItem, element);
                                console.info('photo index: ' + profilePhotoS.photoItemIndex);
                                history.pushState(null, null, '/profile/' + (scope.mc.photoItem.uid.username || scope.mc.photoItem.uid.uid) + '/photos/item/' + scope.mc.photoItem._id.$id + '/');
                                //$location.path('/profile/'+(scope.mc.photoItem.uid.username || scope.mc.photoItem.uid.uid)+'/photos/item/'+scope.mc.photoItem._id.$id+'/');
                            });

                        }

                        if (e.which == 37 && isPhotoSliderDisabled !== true) {
                            scope.$apply(function () {
                                scope.mc.photoItem = profilePhotoS._previousPhotoItem();
                                console.info('photo index: ' + profilePhotoS.photoItemIndex);
                                setPhotoSize(scope.mc.photoItem, element);
                                history.pushState(null, null, '/profile/' + (scope.mc.photoItem.uid.username || scope.mc.photoItem.uid.uid) + '/photos/item/' + scope.mc.photoItem._id.$id + '/');
                                //$location.path('/profile/'+(scope.mc.photoItem.uid.username || scope.mc.photoItem.uid.uid)+'/photos/item/'+scope.mc.photoItem._id.$id+'/');
                            });

                        }

                });
                var modalHideListener = scope.$on('modal.hide', function () {
                    console.info(scope);
                    if (scope.$isShown === false) {
                        w.unbind('resize');
                        w.unbind('keyup');
                        modalHideListener(); //remove it from listeners
                    } else {
                        var myEl = angular.element(document.querySelector('body'));
                        myEl.addClass('modal-open');
                    }
                });
                if (typeof $stateParams.photoId !== 'undefined') {
                    console.info('undefined');
                    profilePhotoS._getPhotoItem($stateParams.uid, $stateParams.photoId, function (response) {
                        scope.mc = {};
                        scope.mc.photoItem = {};
                        scope.mc.photoItem = response;
                        var photos = profilePhotoS._getPhotos();
                        photoCount = photos.length;
                        console.info('photos: ' + photoCount);
                        console.info('photo index: ' + profilePhotoS.photoItemIndex);
                        scope.$watch(function () {
                            return userService.userSource;
                        }, function (newValue, oldValue) {
                            scope.mc.user = userService._get();
                        });
                        reload(response, element);
                        scope.$watch(scope.getWindowDimensions, function (newValue, oldValue) {
                            setPhotoSize(scope.mc.photoItem, element);
                        }, true);
                        var photoItemEl = angular.element(document.querySelector('#photo-item'));
                        if (scope.mc.photoItem.photo.width > scope.mc.photoItem.height) {
                            photoItemEl.attr('style', 'width: 100%; height: auto;');
                        } else {
                            photoItemEl.attr('style', 'width: auto; height: 100%;');
                        }
                    });
                }
                element.bind('click', function (e) {
                    getClickPosition(e);
                    var halfScreenX = e.currentTarget.clientWidth / 2;
                    if (halfScreenX > xPosition)
                        scope.$apply(function () {
                            scope.mc.photoItem = profilePhotoS._previousPhotoItem();
                        });
                    if (halfScreenX < xPosition)
                        scope.$apply(function () {
                            scope.mc.photoItem = profilePhotoS._nextPhotoItem();
                        });
                    console.info('photo index: ' + profilePhotoS.photoItemIndex);
                    history.pushState(null, null, '/profile/' + (scope.mc.photoItem.uid.username || scope.mc.photoItem.uid.uid) + '/photos/item/' + scope.mc.photoItem._id.$id + '/');
                    //$location.path('/profile/'+(scope.mc.photoItem.uid.username || scope.mc.photoItem.uid.uid)+'/photos/item/'+scope.mc.photoItem._id.$id+'/');
                    setPhotoSize(scope.mc.photoItem, element);
                });
            }
        }
    }])
    /* Photo Comments */
    .directive('photoCommentInput', ['profilePhotoService', function (profilePhotoS) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('focus', function (e) {
                    console.info('focus');
                    profilePhotoS._setPhotoSliderDisabled(true);
                });
                element.bind('blur', function (e) {
                    console.info('blur');
                    profilePhotoS._setPhotoSliderDisabled(false);
                });
                element.bind('keydown', function (e) {
                    if (e.which === 13) {
                        console.info('entered');
                        angular.element('#comment-submit').trigger('click');
                        element.trigger('blur');
                        e.preventDefault();
                    }
                });
            }
        }
    }])
    .directive('photoCommentButton', ['profilePhotoService', function (profilePhotoS) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('click', function (e) {
                    if (scope.photoCommentModel !== '')
                        profilePhotoS._postComment(attrs.photoId, scope.photoCommentModel, function (response) {
                            if (typeof response.photos.items !== 'undefined')
                                profilePhotoS._unshiftComment(response.photos.items.comments[0]);
                            scope.photoCommentModel = '';
                        });
                });
            }
        }
    }]);