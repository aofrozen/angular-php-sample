angular.module('socialSample.notification', ['socialSample.notificationDirective']).controller('notificationController', ['$scope', 'ajaxService', 'notificationService', function ($scope, $http, notificationService) {
    console.info('notification controller');
    $scope.mc = {};
    $scope.mc.notifications = {};
    notificationService._load(function (data) {
        $scope.mc.notifications = data;
    });

}]);