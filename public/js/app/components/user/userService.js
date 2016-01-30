angular.module('socialSample.userService', [])
    .factory('userService', ['ajaxService', function ($http) {
        var userService = {};
        userService.userSource = {};
        userService._extendUser = function (source) { //need to test and improve this. due to friends is child-child
            if (typeof source.user === 'undefined' && source === null) {
                return;
            }
            angular.extend(userService.userSource, source);
        };
        userService._save = function (source, callback) { //private
            if (typeof source.user === 'undefined' && source === null) {
                callback();
                return;
            }
            userService.userSource = source.user;
            callback();
        };
        userService._get = function () {
            return userService.userSource;
        };
        userService._reset = function (callback) {
            $http._ajax('get', '/ajax/user/', '', function (result) {
                userService._save(result.data, function(){
                    callback(userService._get());
                });
            });
        };
        userService._exists = function () {
            return (typeof userService.userSource.uid !== 'undefined');
        };
        userService._load = function (callback) {
            if (userService._exists() === false) {
                $http._ajax('get', '/ajax/user/', '', function (result) {
                    userService._save(result.data, function () {
                        callback(userService._get())
                    });
                });
            } else {
                console.warn('cached');
                callback(userService._get());
            }
        };
        return userService;
    }]);