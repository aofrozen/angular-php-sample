angular.module('socialSample.profileService', [])
    .service('profileService', ['ajaxService', 'profileFriendService', function ($http, friendService) {
        var profileService = {};
        profileService.profileSource = {};
        profileService._extendProfile = function (source) {
            if (typeof source.profile === 'undefined') {
                return;
            }
            angular.extend(profileService.profileSource, source.profile);
        };
            profileService._wallRotationToggle = function(callback) {
            console.info('wall rotation toggle');
            //var rotation;
            //if(typeof profileService.profileSource.wallRotation === 'undefined') {
            //    profileService.profileSource.wallRotation = true;
            //}
            angular.extend(profileService.profileSource, {wallRotation: true});
            profileService._saveWallStyles(2);
            callback(profileService.profileSource._get());
        };
        profileService._wallPositionToggle = function(callback){
            console.info('wall position toggle');
            var position;
            if(typeof profileService.profileSource.wallPosition === 'undefined')
                profileService.profileSource.wallPosition = 'top';
            switch(profileService.profileSource.wallPosition)
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
            angular.extend(profileService.profileSource, {wallPosition: position});
            profileService._saveWallStyles(2);
            callback(profileService._get());
        };
        profileService._saveWallStyles = function(duration) {
            var wallData = {wallPosition: profileService.profileSource.wallPosition, wallRotation: profileService.profileSource.wallRotation};
            $http._ajax('put', '/ajax/profile/wall', wallData, function(response){
                angular.extend(profileService.profileSource, {wallRotation: false});
            });
        };
        profileService._save = function (source, callback) {
            if (typeof source.profile === 'undefined') {
                profileService.profileSource = false;
                callback();
                return;
            }
            profileService.profileSource = source.profile; //only profile data
            callback();
        };
        profileService._get = function () {
            return profileService.profileSource;
        };
        profileService._load = function (pid, callback) {
            $http._ajax('get', '/ajax/profile/' + pid, '', function (result) {
                profileService._save(result.data, function () {
                    friendService._save(result.data, function () {
                        callback(profileService._get(), friendService._get());
                    })
                });
            });
        };
        return profileService;
    }]);