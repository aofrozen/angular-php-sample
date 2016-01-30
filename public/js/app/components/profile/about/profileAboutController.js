
angular.module('socialSample.profileAbout', []).controller('profileAboutController', ['$scope', 'profileAboutService', function ($scope, profileAboutS) {
    console.info('profile about');
    $scope.mc.about = {};
    profileAboutS._load($scope.pid, function(aboutSource){
        $scope.mc.about = aboutSource;
    });
}]);