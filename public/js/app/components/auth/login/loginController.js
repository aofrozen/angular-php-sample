
angular.module('socialSample.authLogin', []).controller('loginController', ['$scope', 'ajaxService', '$alert', function ($scope, $http, $alert) {
    this.login = function () {
        $http._ajax('post', '/login', $scope.user, function (result) {

            var response = result.data;
            if (response.success === true) {
                if (response.redirect === null) {
                    window.location = '/home';
                } else {
                    window.location = response.redirect;
                }
            } else if (response.success === false) {
                $alert({container:'#alertMessage', title:response.alerts.title, content:response.alerts.message, type:response.alerts.type, show:true, duration:3});
            }
        });
    };
}]);