
angular.module('socialSample.socialSample', [])
    .config(['$dropdownProvider', '$popoverProvider', function ($dropdownProvider, $popoverProvider) {
        angular.extend($dropdownProvider.defaults, {
            html: true
        });
        angular.extend($popoverProvider.defaults, {
            html: true,
            placement: "bottom",
            autoClose: true
        })
    }])
    .controller('navController', ['$scope', 'userService', function ($scope, userService) {
        $scope.mc = {};
        $scope.mc.user = {};
        userService._load(function (data) {
            $scope.mc.user = data;
        });

    }]);