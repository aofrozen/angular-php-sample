/**
 * Created by aofrozen on 4/29/2015.
 */
angular.module('socialSample.router', ['ct.ui.router.extras']).config(['$stateProvider', '$locationProvider', '$urlRouterProvider', '$stickyStateProvider', function ($stateProvider, $locationProvider, $urlRouterProvider, $stickyStateProvider) {
    //$stickyStateProvider.enableDebug(true);
    $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
    });
    //$urlRouterProvider.otherwise('/404');
    $urlRouterProvider.rule(function ($injector, $location) {
        var path = $location.url();

        // check to see if the path already has a slash where it should be
        if (path[path.length - 1] === '/' || path.indexOf('/?') > -1) {
            return;
        }

        if (path.indexOf('?') > -1) {
            return path.replace('?', '/?');
        }
        if (path.indexOf('#_=_') > -1) { //facebook hash
            return path.replace('#_=_', '');
        }

        return path + '/';
    });
    $stateProviderRef = $stateProvider;
    //$urlRouterProvider.otherwise("/home");
    $stateProvider.state('home', {
        url: '/home/',
        views: {'top': {templateUrl: '/home?ng-view=false'}},
        sticky: true
    }).state('settings', {
        url: '/settings/',
        views: {'top': {templateUrl: '/settings?ng-view=false'}},
        sticky: true
    }).state('404', {
        url: '/404/',
        views: {'top': {templateUrl: '/404?ng-view=false'}},
        sticky: true
    });
}]).run(['$urlMatcherFactory', '$rootScope', '$previousState', '$state', '$location', '$route', '$stickyState',
    function ($urlMatcherFactory, $rootScope, $previousState, $state, $location, $route, $stickyState) {

        $rootScope.$on('$locationChangeStart', function (e, newUrl, oldUrl, newState, oldState) {
            var rePhotoItem = /(http|https):\/\/(www\.|)socialSample.(dev|net)\/profile\/(.*)\/photos\/item/i;
            var reMessage = /(http|https):\/\/(www.|)socialSample.(dev|net)\/messages\/(.*)(\/|)/i;
            console.info($stickyState);

            if (((rePhotoItem.exec(newUrl)) !== null && (rePhotoItem.exec(oldUrl)) !== null) || ((reMessage.exec(newUrl) !== null || (reMessage.exec(oldUrl) !== null)))) {
                // View your result using the m-variable.
                // eg m[0] etc.
                console.info('changed');
                console.info(e);
                console.info(newUrl);
                console.info(oldUrl);
                console.info(newState);
                console.info(oldState);
            }else{
                console.info("RESET!");
                $stickyState.reset("*");
            }


        });
        //Location path change without reload module


        var profileUrlMatcher = $urlMatcherFactory.compile("/profile/:uid/"); //timeline as default
        //profile about
        var profileAboutUrlMatcher = $urlMatcherFactory.compile("/profile/:uid/about/");
        //profile photos
        var profilePhotosUrlMatcher = $urlMatcherFactory.compile("/profile/:uid/photos/");
        //profile all photos
        var profileAllPhotosUrlMatcher = $urlMatcherFactory.compile("/profile/:uid/photos/all/");
        //profile photo albums
        var profilePhotoAlbumsUrlMatcher = $urlMatcherFactory.compile("/profile/:uid/photos/albums/");
        //profile photo album
        var profilePhotoAlbumPhotosUrlMatcher = $urlMatcherFactory.compile("/profile/:uid/photos/albums/:id/");
        //profile photo item
        var profilePhotoItemUrlMatcher = $urlMatcherFactory.compile("/profile/:uid/photos/item/:photoId/");
        //profile friends
        var profileFriendsUrlMatcher = $urlMatcherFactory.compile("/profile/:uid/friends/");
        //message
        var messageUrlMatcher = $urlMatcherFactory.compile("/messages/:uid/"); //optional UID
        //notification
        //feed video item
        var feedVideoItemUrlMatcher = $urlMatcherFactory.compile("/feeds/item/:id/video/"); //optional UID
        //message

        //feed item
        var feedItemUrlMatcher = $urlMatcherFactory.compile("/feeds/item/:id/"); //optional UID

        $stateProviderRef
            .state('feedItem', {
                url: feedItemUrlMatcher,
                views: {
                    'top': {
                        templateUrl: function(params){
                            return '/feeds/item/' + params.id+'/?ng-view=false';
                        }
                    }
                }
            })
            .state('feedVideoItem', {
                url: feedVideoItemUrlMatcher,
                onEnter: ['$modal', '$state', '$stateParams', function ($modal, $state, $stateParams) {
                    $previousState.memo("modalInvoker");
                    feedItemModal = $modal({template: '/view/feeds/item/'+ $stateParams.id +'?ng-view=false', show: true, backdropAnimation: 'modal-backdrop', animation: ''});
                }],
                template: '<div ui-view></div>'
            })
            .state('profile.photos', {
                url: profilePhotosUrlMatcher,
                views: {
                    "profile-content": {
                        templateUrl: function (params) {
                            return '/view/profile/' + params.uid + '/photos/';
                        }
                        },
                    "photo-content@profile.photos": {
                        templateUrl: function (params) {
                            return '/view/profile/' + params.uid + '/photos/all';
                        }
                    }
                },
                data: {profileSelectedTab: "photos"}
            })
            .state('profilePhotoItem', {
                url: profilePhotoItemUrlMatcher,
                onEnter: ['$modal', '$state', function ($modal, $state) {
                    if(typeof photoItemModal === 'undefined')
                    {
                        $previousState.memo("modalInvoker");
                        $rootScope.photoItemModal = photoItemModal = $modal({template: '/view/photos/item/', show: true, backdropAnimation: 'modal-backdrop', animation:''});
                    }

                }],
                template: '<div ui-view></div>',
                data: {profileSelectedTab: "photos"}
            })
            //Profile Photo (non-modal)
            .state('profile.photos.albums', {
                url: profilePhotoAlbumsUrlMatcher,
                views: {
                    "photo-content": {
                        templateUrl: function (params) {
                            return '/view/profile/' + params.uid + '/photos/albums';
                        }
                    }
                },
                data: {profileSelectedTab: "photos"}

            })
            .state('profile.photos.albumPhotos', {
                url: profilePhotoAlbumPhotosUrlMatcher,
                views: {
                    "profile-content": {
                        templateUrl: function (params) {
                            return '/view/profile/' + params.uid + '/photos/albums/'+ params.id;
                        }
                    }
                }
            })
            .state('profile', {
                url: profileUrlMatcher,
                views: {
                    'top': {
                        templateUrl: function (params) {
                            return '/profile/' + params.uid + '/?ng-view=false';
                        }
                    }
                },
                data: {profileSelectedTab: "timeline"},
                sticky: true
            })
            .state('profile.friends', {
                url: profileFriendsUrlMatcher,
                views: {
                    "profile-content": {
                        templateUrl: function (params) {
                            return '/view/profile/' + params.uid + '/friends';
                        }
                    }
                },
                data: {profileSelectedTab: "friends"}
            })
            .state('profile.about', {
                url: profileAboutUrlMatcher,
                views: {
                    "profile-content": {
                        templateUrl: function (params) {
                            return '/view/profile/' + params.uid + '/about';
                        }
                    }
                },
                data: {profileSelectedTab: "about"}
            })
            .state('message', {
                url: messageUrlMatcher,
                onEnter: ['$modal', '$state', function ($modal, $state) {
                    $previousState.memo("modalInvoker");
                    messageModal = $modal({template: '/messages/?ng-view=false', show: true, backdropAnimation: 'modal-backdrop', animation:''});

                }],
                template: '<div ui-view></div>'
            });
        $rootScope.$on('modal.hide', function () {
            if (typeof messageModal !== 'undefined' && messageModal.$isShown === false) {
                if($previousState.get("modalInvoker").state !== null && $state.current.name == 'message'){
                    messageModal.destroy();
                    delete messageModal;
                    $previousState.go("modalInvoker");
                }else if($state.current.name == 'message'){
                    messageModal.destroy();
                    delete messageModal;
                    $state.go('home');
                }
            }
            if (typeof photoItemModal !== 'undefined' && photoItemModal.$isShown === false) {
                console.info('hiding');
                if($previousState.get("modalInvoker").state !== null && $state.current.name == 'profilePhotoItem'){
                    console.info('go previous page');
                    photoItemModal.destroy();
                    photoItemModal = undefined;
                    $previousState.go("modalInvoker");
                }else if($state.current.name == 'profilePhotoItem'){
                    photoItemModal.destroy();
                    photoItemModal = undefined;
                    $state.go('home');
                }else{
                    console.info('nothing');
                }
            }

            if (typeof feedItemModal !== 'undefined' && feedItemModal.$isShown === false) {
                if($previousState.get("modalInvoker").state !== null && $state.current.name == 'feedVideoItem'){
                    $previousState.go("modalInvoker");
                }else if($state.current.name == 'feedVideoItem'){
                    $state.go('home');
                }
            }
        });
    }
]);