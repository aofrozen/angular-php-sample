angular.module('socialSample.profileFriendService', []).service('profileFriendService', ['ajaxService', function($http){
    var friendService = {};
    var friendSource = {};
    friendService._save = function (source, callback) {
        if(typeof source.friends === 'undefined')
        {
            callback();
            return;
        }
        friendSource = source.friends; //only profile data
        callback();
    };
    friendService._get = function () {
        return friendSource;
    };
    friendService._load = function (pid, callback) {
        $http._ajax('get', '/ajax/friends/' + pid, '', function (result) {
            friendService._save(result.data, function(){
                callback(friendService._get());
            });
        });
    };
    return friendService;
}]);