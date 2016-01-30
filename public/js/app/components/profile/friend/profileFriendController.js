
angular.module('socialSample.profileFriend', []).controller('profileFriendController', ['$scope', 'profileFriendService', 'userService', function ($scope, profileFriendService, userService) {
    console.info('friendController');
    $scope.mc = {};
    $scope.mc.friends = {};
    $scope.mc.user = {};
    profileFriendService._load($scope.pid, function(data){
        $scope.mc.friends = data;
    });
    userService._load(function(data){
       $scope.mc.user = data;
    });

}]);