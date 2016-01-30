angular.module('socialSample.profilePhotoService', []).service('profilePhotoService', ['ajaxService', function ($http) {
    var profilePhotoService = this;
    var photoData = {};
    var photoItemData = {}; //not sure if should use it due to photoData can be used for photo item too.
    var photoAlbumData = {};
    var albumPhotoData = {};
    var activePage = 'photos'; //default
    profilePhotoService.photoItemIndex = 0;
    profilePhotoService.__editPhotoItemModal = {};
    profilePhotoService._photoSliderDisabled = false;

    /* Photo Misc. */
    profilePhotoService._setPhotoSliderDisabled = function(val){
        profilePhotoService._photoSliderDisabled = val;
    };
    profilePhotoService._isPhotoSliderDisabled = function(){
        return profilePhotoService._photoSliderDisabled;
    };
    profilePhotoService._activeAlbumPhotos = function () {
        activePage = 'albumPhotos';
    };

    profilePhotoService._activePhotos = function () {
        activePage = 'photos';
    };

    profilePhotoService._getActivePage = function () {
        return activePage;
    };


    /* Photo Item */
    profilePhotoService._pushPhotoItem = function (photoItem, callback) {
        if (typeof photoData !== 'undefined')
            photoData.items.unshift(photoItem);
        callback();
    };

    profilePhotoService._changePhotoItem = function (photoItemIndex, data) {
        if (typeof photoData.items === 'undefined')
            return false;
        photoData.items[photoItemIndex] = data;
    };

    profilePhotoService._nextPhotoItem = function () {
        var photos = {};
        photos = profilePhotoService._getPhotos();
        profilePhotoService.photoItemIndex++;
        if (photos.items.length <= profilePhotoService.photoItemIndex)
            profilePhotoService.photoItemIndex = 0;
        return photos.items[profilePhotoService.photoItemIndex];
    };

    profilePhotoService._previousPhotoItem = function () {
        var photos = {};
        photos = profilePhotoService._getPhotos();
        profilePhotoService.photoItemIndex--;
        if (0 > profilePhotoService.photoItemIndex)
            profilePhotoService.photoItemIndex = photos.items.length - 1;
        return photos.items[profilePhotoService.photoItemIndex];
    };

    profilePhotoService._getCurrentPhotoItem = function () {
        var photos = profilePhotoService._getPhotos();
        if (typeof photos.items === 'undefined')
            return false;
        return photos.items[profilePhotoService.photoItemIndex];
    };

    profilePhotoService._getPhotoItem = function (uid, photoId, callback) {
        var photos = {};
        photos = profilePhotoService._getPhotos();
        if (typeof photos.items === 'undefined') {
            profilePhotoService._loadPhotosWithPhotoId(uid, photoId, function (photos) {
                if (typeof photos.items === 'undefined') //redirect to 404
                    callback(null);
                for (var x = 0; x < photos.items.length; x++) {
                    if (photos.items[x]._id.$id == photoId) {
                        profilePhotoService.photoItemIndex = x;
                        break;
                    }
                }
                callback(photos.items[profilePhotoService.photoItemIndex]);
            });
        } else {
            for (var x = 0; x < photos.items.length; x++) {
                if (photos.items[x]._id.$id == photoId) {
                    profilePhotoService.photoItemIndex = x;
                    break;
                }
            }
            callback(photos.items[profilePhotoService.photoItemIndex]);
        }
    };

    profilePhotoService._deletePhotoItem = function (callback) {
        photoItemData = profilePhotoService._getCurrentPhotoItem();
        if (photoItemData === false)
            return false;
        var deletePhotoItemData = {'uid' : photoItemData.uid.uid, 'photoId' : photoItemData._id.$id};
        $http._ajax('delete', '/ajax/photos', deletePhotoItemData, function (response) {
            callback();
        });
    };


    /* Photos */
    profilePhotoService._extendPhotos = function (source) {
        if (typeof source.photos === 'undefined')
            return;
        angular.extend(photoData, source.photos);
    };

    profilePhotoService._savePhotos = function (source, callback) {
        photoData = source.photos; //only photos data
        callback();
    };
    profilePhotoService._getPhotos = function () {
        return photoData;
    };
    profilePhotoService._loadPhotosWithPhotoId = function (uid, photoId, callback) {
        $http._ajax('get', '/ajax/photos/' + uid + '/item/' + photoId, '', function (response) {
            profilePhotoService._savePhotos(response.data, function () {
                callback(profilePhotoService._getPhotos());
            });
        });
    };
    profilePhotoService._loadPhotos = function (pid, callback) {
        $http._ajax('get', '/ajax/photos/' + pid, '', function (response) {
            profilePhotoService._savePhotos(response.data, function () {
                callback(profilePhotoService._getPhotos());
            });
        });
    };


    /* Album Photos */
    profilePhotoService._saveAlbumPhotos = function (source, callback) {
        albumPhotoData = source.photos; //only album photos data
        callback();
    };

    profilePhotoService._loadAlbumPhotos = function (pid, albumId, callback) {
        $http._ajax('get', '/ajax/photos/' + pid + '/albums/' + albumId, '', function (result) {
            profilePhotoService._savePhotos(result.data, function () {
                callback(profilePhotoService._getPhotos());
            });
        });
    };

    profilePhotoService._getAlbumPhotos = function () {
        return albumPhotoData;
    };


    /* Photo Albums */
    profilePhotoService._getPhotoAlbums = function () {
        return photoAlbumData;
    };

    profilePhotoService._extendPhotoAlbums = function (source) {
        if (typeof source.photoAlbums === 'undefined')
            return;
        angular.extend(photoAlbumData, source.photoAlbums);
    };

    profilePhotoService._savePhotoAlbums = function (source, callback) {
        photoAlbumData = source.photoAlbums; //only photo albums data;
        callback();
    };

    profilePhotoService._getPhotoAlbums = function () {
        return photoAlbumData;
    };

    profilePhotoService._loadPhotoAlbums = function (pid, callback) {
        $http._ajax('get', '/ajax/photos/' + pid + '/albums', '', function (response) {
            profilePhotoService._savePhotoAlbums(response.data, function () {
                callback(profilePhotoService._getPhotoAlbums());
            });
        });
    };


    /* Photo Coments */
    profilePhotoService._deleteComment = function(photoId, commentId, commentIndex, callback){
        var deleteCommentData = {'photoId' : photoId, 'commentId' : commentId};
      $http._ajax('delete', '/ajax/photos/item/comments', deleteCommentData, function(response){
          profilePhotoService._spliceComment(commentIndex);
          callback();
      })
    };
    profilePhotoService._postComment = function (photoId, comment, callback) {
        var postCommentData = {'photoId': photoId, 'comment': comment};
        $http._ajax('post', '/ajax/photos/item/comments', postCommentData, function (response) {
            callback(response.data);
        });
    };
    profilePhotoService._spliceComment = function(commentIndex){
      if(typeof commentIndex === 'undefined')
      return false;
        console.info(photoData.items[profilePhotoService.photoItemIndex].comments.splice(commentIndex, 1));
    };
    profilePhotoService._unshiftComment = function (comment) {
        if (typeof comment === 'undefined')
            return false;
        photoData.items[profilePhotoService.photoItemIndex].comments.unshift(comment);
    };
    return profilePhotoService;
}]);