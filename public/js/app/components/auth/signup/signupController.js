
angular.module('socialSample.authSignup', []).controller('signupController', ['$scope', 'ajaxService', '$alert', function ($scope, $http, $alert) {
    this.signup = function () {
        $http._ajax('post', '/signup', $scope.user, function (result) {
            var response = result.data;
            if (response.success === true) {
                if (response.redirect === null) {
                    window.location = '/home';
                } else {
                    window.location = response.redirect;
                }
            } else if (result.data.success === false) {
                $alert({container:'#alertMessage', title:response.alerts.title, content:response.alerts.message, type:response.alerts.type, show:true, duration:3});
            }
        });
    };
}]);