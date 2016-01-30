angular.module('socialSample.settingsService', [])
    .factory('settingsService', ['ajaxService', function($http){
        var settingsService = {};
        settingsService.converter = function (selectedValue, options) {
            for (var x = 0; x < options.length; x++) {
                if (options[x].label == selectedValue)
                    return x;
            }
            return 0;
        };
        settingsService._submit = function(settingsSource, callback) {
            $http._ajax('put', '/ajax/settings', settingsSource, function (response) {
                callback(response.data);
            });
        };
        return settingsService;
    }]);