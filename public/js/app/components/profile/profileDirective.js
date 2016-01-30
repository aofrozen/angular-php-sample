angular.module('socialSample.profileDirective', [])
    .directive('photoContentData', ['$compile', function ($compile) {
        return function (scope, element, attrs) {
            scope.$watch(
                function (scope) {
                    // watch the 'compile' expression for changes
                    return scope.$eval(attrs.photoContentData);
                },
                function (value) {
                    // when the 'compile' expression changes
                    // assign it into the current DOM
                    element.html(value);

                    // compile the new DOM and link it to the current
                    // scope.
                    // NOTE: we only compile .childNodes so that
                    // we don't get into infinite loop compiling ourselves
                    $compile(element.contents())(scope);
                }
            );
        };
    }]).directive('addFriendButton', ['ngProgress', 'ajaxService', '$alert', function (ngProgress, $http, $alert) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('mouseover', function(){

                   if(element.html() == 'Friends')
                   {
                       element.html('Unfriend');
                   }
                });
                element.bind('mouseout', function(){
                    if(element.html() == 'Unfriend')
                        element.html('Friends');
                   //element.html(scope.mc.friends.addFriendButtonName);
                });
                element.bind('click', function () {
                    ngProgress.start();
                    var data = {fid : attr.addFriendButton };
                    if(scope.mc.friends.addFriendButtonName == 'Friends')
                    {
                        $http._ajax('delete', '/ajax/friends/', data, function (result) {
                            var response = result.data;
                            scope.mc.friends.addFriendButtonName = response.friends.addFriendButtonName;
                            element.html(response.friends.addFriendButtonName);
                            if(typeof response.alerts !== "undefined")
                                $alert({content:response.alerts.message, type:response.alerts.type, show:true, placement:'top', duration:5});
                            ngProgress.complete();
                        });
                    }else{
                        $http._ajax('post', '/ajax/friends/', data, function (result) {
                            var response = result.data;
                            scope.mc.friends.addFriendButtonName = response.friends.addFriendButtonName;
                            element.html(response.friends.addFriendButtonName);
                            if(typeof response.alerts !== "undefined")
                                $alert({content:response.alerts.message, type:response.alerts.type, show:true, placement:'top', duration:5});
                            ngProgress.complete();
                        });
                    }

                });
            }
        };
    }])
    .directive('profileWallPositionToggle', ['profileService', function(profileService){
        return{
            restrict: 'A',
            link: function(scope, element, attr){
                element.bind('click', function(e){
                    profileService._wallPositionToggle(function(response){
                        scope.$apply(function(){
                            scope.mc.profile = response;
                        });
                        console.info(response);
                    });
                });
            }
        }
    }])
    .directive('profileWallRotationToggle', ['profileService', function(profileService){
        return{
            restrict: 'A',
            link: function(scope, element, attr){
                element.bind('click', function(e){
                    profileService._wallRotationToggle(function(response){
                        scope.$apply(function(){
                            scope.mc.profile = response
                        });
                        console.info(response);
                    });
                });
            }
        }
    }]);