angular.module('socialSample.notificationService', []).service('notificationService', ['ajaxService', function ($http) {
    var notificationService = {};
    var notificationSource = {};
    notificationService._extendNotification = function (source) {
        angular.extend(notificationSource, source.notifications);
    };
    var _extendAlerts = function (source) {
        angular.extend(notificationSource, source.alerts);
    };
    notificationService._save = function (source, callback) {
        if (typeof source.notifications === 'undefined') {
            callback();
            return;
        }
        notificationSource = source.notifications;
        callback();
    };
    notificationService._get = function () {
        return notificationSource;
    };
    notificationService._load = function (callback) {
        $http._ajax('get', '/ajax/notifications/', '', function (result) {
            notificationService._save(result.data, function () {
                callback(notificationService._get());
            });
        });
    };
    return notificationService;
}]);