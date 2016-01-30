
angular.module('everlist.followDirective', [])
    .directive('follow', [function () {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                scope.fid = attr.fid; //not sure why it is used
                element.val((attr.followstatus) ? attr.followstatus : 'Follow');
                element.bind('click', function () {
                    scope.follow(attr.fid); //profile component
                    if (element.val() == 'Following') {
                        element.val('Follow');
                    } else if (element.val() == 'Follow') {
                        element.val('Following');
                    }
                });
            }
        }
    }]);