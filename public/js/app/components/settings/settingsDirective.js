
angular.module('socialSample.settingsDirective', [])
    .directive('settingsSubmitButton', ['ajaxService', 'settingsService', 'userService', '$alert', function($http, settingsService, userService, $alert){
        /*
         $scope.settings.profile.gender = $scope.settings.profile.gender.label;
         $scope.settings.profile.race = $scope.settings.profile.race.label;
         console.info($scope.settings);
         $http._ajax('put', '/ajax/settings', $scope.settings, function (result) {
         console.info('submitted');
         console.info(result);
         if(typeof result.data !== 'undefined')
         {
         $scope.settings = result.data;
         $scope.settings.profile.gender = $scope.genderOptions[converter($scope.settings.profile.gender, $scope.genderOptions)];
         $scope.settings.profile.race = $scope.raceOptions[converter($scope.settings.profile.race, $scope.raceOptions)];
         userService._reset(function(userSource){
         $scope.mc.user = userSource;
         });
         }
         });
         */
        console.info('settings submit button');
        return {
            restrict: 'EA',
            link: function(scope, element, attr){
                element.bind('click', function(){
                    scope.settings.profile.gender = scope.settings.profile.gender.label;
                    scope.settings.profile.race = scope.settings.profile.race.label;
                    settingsService._submit(scope.settings, function(response){
                        console.info('submitted');
                        if(typeof response !== 'undefined')
                        {
                            if(typeof response.alerts !== 'undefined')
                            {
                                $alert({
                                    title: response.alerts.title,
                                    content: response.alerts.message,
                                    type: response.alerts.type,
                                    show: true,
                                    placement: 'top',
                                    duration: 5
                                });
                            }
                            if(typeof response.profile !== 'undefined' && typeof response.auth !== 'undefined' && typeof response.privacy !== 'undefined')
                            {
                                scope.settings = response;
                                scope.settings.profile.gender = scope.genderOptions[settingsService.converter(scope.settings.profile.gender, scope.genderOptions)];
                                scope.settings.profile.race = scope.raceOptions[settingsService.converter(scope.settings.profile.race, scope.raceOptions)];
                                userService._reset(function(userSource){
                                    scope.mc.user = userSource;
                                });
                            }
                        }
                    });
                });

            }
        }
}]);