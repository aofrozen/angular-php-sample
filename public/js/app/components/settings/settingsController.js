angular.module('socialSample.settings', ['socialSample.settingsDirective']).controller('settingsController', ['ajaxService', 'userService', '$scope', function ($http, userService, $scope) {
    console.info('Settings is fired!');
    $scope.init = function () {
        $http._ajax('get', '/ajax/settings', '', function (result) {
            console.info('recieved data');
            $scope.settings = result.data;
            $scope.mc = result.data;
            $scope.settings.profile.gender = $scope.genderOptions[converter($scope.settings.profile.gender, $scope.genderOptions)];
            $scope.settings.profile.race = $scope.raceOptions[converter($scope.settings.profile.race, $scope.raceOptions)];
        });
    };
    function converter(selectedValue, options) {
        for (var x = 0; x < options.length; x++) {
            if (options[x].label == selectedValue)
                return x;
        }
        return 0;
    }
    /* Forms */
    /* Profile */
    $scope.genderOptions = [
        {label: 'Neutral', value: 0},
        {label: 'Male', value: 1},
        {label: 'Female', value: 2}
    ];
    console.info($scope.genderOptions);
    $scope.raceOptions = [
        {label: 'Neutral', value: 0},
        {label: 'White', value: 1},
        {label: 'Black', value: 2},
        {label: 'Asian', value: 3},
        {label: 'Hispanic', value: 4},
        {label: 'Other', value: 5}

    ];
    $scope.countryOptions = [
        {
            label: 'United States', value: 1
        }
    ];
    /* Privacy */
    $scope.wcmyOptions = [
        {label: 'Everyone', value: 0},
        {label: 'Only Friends', value: 1}
    ];
    $scope.wcrypOptions = [
        {label: 'Everyone', value: 1},
        {label: 'Only Friends', value: 2}
    ];
    $scope.wcrypnOptions = [
        {label: 'Everyone', value: 1},
        {label: 'Only Friends', value: 2},
        {label: 'Only Me', value: 2}
    ];
    $scope.wcryeaOptions = [
        {label: 'Everyone', value: 1},
        {label: 'Only Friends', value: 2},
        {label: 'Only Me', value: 3}
    ]

}]);