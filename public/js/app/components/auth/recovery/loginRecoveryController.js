
angular.module('socialSample.authRecovery', []).controller('loginRecoveryController', ['$scope', 'ajaxService', function ($scope, $http) {
    this.send = function () {
        console.log('Auth Recovery');
        console.log($scope.user);
        $http._ajax('post', '/ajax/recover', $scope.user, function (result) {
            console.log(result);
            if (result.data.success === true) {

            } else if (result.data.success === false) {
                console.warn('failed');
            }
        })
    };
}]);