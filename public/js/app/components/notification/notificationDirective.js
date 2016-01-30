angular.module('socialSample.notificationDirective', [])
    .directive('acceptFriendRequestButton', ['ajaxService', function ($http) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('click', function () {
                    console.info('accept');
                    if (!scope.$$phase)
                        scope.$apply(function(){
                            scope.mc.notifications[attr.index].disabled = true;
                        });
                    var data = {'id': scope.mc.notifications[attr.index]._id.$id };
                    element.html('Accepting...');
                    $http._ajax('put', '/ajax/notifications/', data, function () {
                            //check if successful or not
                        scope.mc.notifications.splice(attr.index, 1);
                    });
                });
            }
        };
    }])
    .directive('rejectFriendRequestButton', ['ajaxService', function ($http) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('click', function () {
                    if (!scope.$$phase)
                        scope.$apply(function(){
                            scope.mc.notifications[attr.index].disabled = true;
                        });
                    var data = {'id': scope.mc.notifications[attr.index]._id.$id };
                    element.html('Rejecting..');
                    $http._ajax('delete', '/ajax/notifications/', data, function () {
                        //check if successful or not
                        scope.mc.notifications.splice(attr.index, 1);
                    });

                });
            }
        };
    }]);