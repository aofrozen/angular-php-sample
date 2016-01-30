
angular.module('socialSample.profile', [])
    .controller('profileController', ['$scope', '$state', 'ajaxService', '$location', '$anchorScroll', 'FileUploader', 'ngProgress', '$alert', '$stateParams', '$document', 'FileUploader', 'profileService', 'userService', 'feedService', function ($scope, $state, $http, $location, $anchorScroll, FileUploader, ngProgress, $alert, $stateParams, $document, FileUploader, profileService, userService, feedService) {
        document.title = 'Profile - socialSample';

        $scope.mc = {}; //Reset mc data when enter other profile page to prevent using previous data
        $scope.mc.user = {};
        $scope.mc.profile = {};
        $scope.mc.friends = {};
        $scope.mc.feeds = {};
        $scope.feedMediaUrlDisabled = false;
        $scope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {
            if (typeof toState.data !== "undefined") {
                if(typeof toState.data.profileSelectedTab)
                $scope.profileSelectedTab = toState.data.profileSelectedTab;
                console.info("tab name: "+$scope.profileSelectedTab);
                console.info(toState);
            }
        });
        $scope.$on('$stateChangeStart', function(){
            ngProgress.start();
        });

        $scope.$on('$stateChangeSuccess', function(){
            ngProgress.complete();
        });

        $scope.$on('$stateChangeError',  function(){
            ngProgress.complete();
        });
        var imageUploader = $scope.imageUploader = new FileUploader({
            url: '/upload/image/post'
        });
        $scope.init = function (pid) {
            $scope.pid = pid;
            console.warn('Profile is intialized. PID is ' + pid);
            userService._load(function(userSource){
                $scope.mc.user = userSource;
                profileService._load(pid, function(profileSource, friendSource){
                    if(profileSource == false)
                    {
                        $state.go('404');
                        console.error('not found');
                    }
                    $scope.mc.profile = profileSource;
                    $scope.mc.friends = friendSource;
                    //ZenPen.editor.init();
                    //ZenPen.ui.init();
                    feedService._load('profile', function (feedSource) {
                        $scope.mc.feeds = feedSource;
                    });
                });
            });


        };
        $scope.sendComment = function () {
            console.info('Send comment is fired. ' + $scope.feedPostModel);
        };
        var wallUploader = $scope.wallUploader = new FileUploader({
            url: '/upload/image/profileWall'
        });
        var avatarURL, wallURL;
        wallUploader.onAfterAddingFile = function (fileItem) {
            /* var uploadOptions = {
             url: '/whatever/uploadfile',
             filters: []
             };
             // File must be jpeg or png
             uploadOptions.filters.push({ name: 'imagefilter', fn: function(item) {
             return item.type == 'image/jpeg' || item.type == 'image/png';
             }});

             // File must not be larger then some size
             uploadOptions.filters.push({ name: 'sizeFilter', fn: function(item) {
             return item.size < 10000;
             }});

             $scope.uploadOptions = uploadOptions;
             */
            fileItem.upload();
        };
        wallUploader.onCompleteItem = function (fileItem, response, status, headers) {
            fileItem.remove();
            ngProgress.complete();
            if(typeof response.profile !== 'undefined')
            {
                if(response.profile.uid == $scope.mc.profile.uid)
                    profileService._extendProfile(response);
            }
            if(typeof response.alerts !== "undefined")
                $alert({title:response.alerts.title, content:response.alerts.message, type:response.alerts.type, show:true, placement:'top', duration:5});
        };
        wallUploader.onProgressItem = function(fileItem, progress) {
            ngProgress.set(progress);
        };
        var avatarUploader = $scope.avatarUploader = new FileUploader({
            url: '/upload/image/profileAvatar'
        });
        avatarUploader.onAfterAddingFile = function (fileItem) {
            fileItem.upload();
        };
        avatarUploader.onCompleteItem = function (fileItem, response, status, headers) {
            fileItem.remove();
            ngProgress.complete();
            if(typeof response.profile !== 'undefined')
            {
                if(response.profile.uid == $scope.mc.profile.uid)
                    profileService._extendProfile(response);
                userService._reset(function(userSource){
                    $scope.mc.user = userSource;
                });
            }
            if(typeof response.alerts !== "undefined")
                $alert({title:response.alerts.title, content:response.alerts.message, type:response.alerts.type, show:true, placement:'top', duration:5});
        };
        avatarUploader.onProgressItem= function (fileItem, progress){
            ngProgress.set(progress);
        };
        this.openLink = function (link) {
            window.open(
                link,
                '_blank'
            );
        };
    }]);