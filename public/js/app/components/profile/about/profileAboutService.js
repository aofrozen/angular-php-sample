
angular.module('socialSample.profileAboutService', []).service('profileAboutService', ['ajaxService', function ($http) {
    var aboutService = {};
    var aboutSource = {};
    aboutService._save = function (source, callback) {
        if(typeof source.about === 'undefined')
        {
            callback();
            return;
        }
        aboutSource = source.about; //only profile data
        callback();
    };
    aboutService._get = function () {
        return aboutSource;
    };
    aboutService._load = function (pid, callback) {
        $http._ajax('get', '/ajax/about/' + pid, '', function (result) {
            aboutService._save(result.data, function(){
                callback(aboutService._get());
            });
        });
    };
    return aboutService;
}]);