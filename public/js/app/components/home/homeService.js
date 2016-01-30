angular.module('socialSample.homeService', [])
    .factory('homeService', ['ajaxService', function ($http) {
        var homeService = {};
        var homeSource = {};
        homeService._extendHome = function (source) {
            if (typeof source.home === 'undefined') {
                return;
            }
            angular.extend(homeSource, source.home);
        };
        homeService._save = function (source, callback) {
            if (typeof source.home === 'undefined') {
                callback();
                return;
            }
            homeSource = source.home;
            callback();
        };
        homeService._get = function () {
            return homeSource;
        };
        homeService._load = function (callback) {
            $http._ajax('get', '/ajax/home/', '', function (result) {
                homeService._save(result.data, function () {
                    callback(homeService._get());
                });
            });
        };
        homeService._saveWallStyles = function(duration) {
            var wallData = {wallPosition: homeSource.wallPosition, wallRotation: homeSource.wallRotation};
            $http._ajax('put', '/ajax/home/wall', wallData, function(response){
                angular.extend(homeSource, {wallRotation: false});
            });
        };
        homeService._wallRotationToggle = function(callback) {
            console.info('wall rotation toggle');
            //if(typeof homeSource.wallRotation === 'undefined')
                //homeSource.wallRotation = true;
            angular.extend(homeSource, {wallRotation: true});
            console.info('rotation: '+rotation);
            homeService._saveWallStyles(2);
            callback(homeSource._get());
        };
        homeService._wallPositionToggle = function(callback){
            console.info('wall position toggle');
            var position;
            if(typeof homeSource.wallPosition === 'undefined')
                homeSource.wallPosition = 'top';
            switch(homeSource.wallPosition)
            {
                case 'top':
                    position = 'center';
                    break;
                case 'center':
                    position = 'bottom';
                    break;
                case 'bottom':
                    position = 'top';
                    break;
                default:
                    position  = 'center';
                    break;
            }
            console.info('position: '+position);
            angular.extend(homeSource, {wallPosition: position});
            homeService._saveWallStyles(2);
            callback(homeService._get());
        };
        return homeService;
    }]);