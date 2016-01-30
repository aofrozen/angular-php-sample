
angular.module('socialSample.home', []).controller('homeController', ['$scope', 'ajaxService', '$location', '$anchorScroll', 'FileUploader', 'ngProgress', '$alert', 'homeService', 'feedService', 'userService', function ($scope, $http, $location, $anchorScroll, FileUploader, ngProgress, $alert, homeService, feedService, userService) {

    $scope.mc = {};
    $scope.mc.user = {};
    $scope.mc.feed = {};
    $scope.mc.home = {};
    $scope.feedMediaUrlDisabled = false;
    document.title = 'Home - socialSample';
    console.info('Home Controller is fired');
    /*userService._load(function(data){
     $scope.mc.user = data;
     });*/
    feedService._load('home', function (data) {
        $scope.mc.feeds = data;
        console.warn($scope.mc.feeds);
    });
    homeService._load(function (data) {
        $scope.mc.home = data;
    });
    userService._load(function (data) {
        $scope.mc.user = data;
    });
    //ZenPen.editor.init();
    //ZenPen.ui.init();
    $scope.$on('$stateChangeStart', function () {
        ngProgress.start();
    });

    $scope.$on('$stateChangeSuccess', function () {
        ngProgress.complete();
    });

    $scope.$on('$stateChangeError', function () {
        ngProgress.complete();
    });


    var homeWallUploader = this.wallUploader = new FileUploader({
        url: '/upload/image/homeWall'
    });
    homeWallUploader.onAfterAddingFile = function (fileItem) {
        fileItem.upload();
    };
    homeWallUploader.onCompleteItem = function (fileItem, response, status, headers) {
        fileItem.remove();
        ngProgress.complete();
        if (typeof response.home !== 'undefined') {
            if (response.home.uid == $scope.mc.home.uid)
                homeService._extendHome(response);
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
    homeWallUploader.onProgressItem = function (fileItem, progress) {
        ngProgress.set(progress);
    };


    this.openLink = function (link) {
        window.open(
            link,
            '_blank'
        );
    };

}]);