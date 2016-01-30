angular.module('socialSample.feedService', []).service('feedService', ['ajaxService', 'profileService', '$alert', function ($http, profileService, $alert) {
    var feedService = {};
    var feedSource = {};
    var feedMediaSource = {};
    feedService._pushComment = function(feedIndex, comment)
    {
        feedSource[feedIndex].comments.unshift(comment);
        console.warn(feedSource);
    };
    feedService._postComment = function(feedId, commentPost, callback) {
        $http._ajax('post', '/ajax/feeds/comments', commentPost, function(response){
            callback(response.data);
        });
    };
    feedService._extendFeed = function(source){
        if(typeof source.feeds === 'undefined')
        {
            return;
        }
        angular.extend(feedSource, source.feeds);
    };
    feedService._saveMedia = function(source){
        feedMediaSource = source;
    };
    feedService._getMedia= function(){
      return feedMediaSource;
    };
    feedService._clearMedia = function()
    {
      feedMediaSource = {};
    };
    feedService._save = function(source, callback){
        if(typeof source.feeds === 'undefined')
        {
            callback();
            return;
        }
        feedSource = source.feeds; //only feeds data
        callback();
    };
    feedService._get = function(){
        return feedSource;
    };
    feedService._load = function(page, callback){
        if(page == 'home')
        {
            $http._ajax('get', '/ajax/feeds/filter/all/next-user/0', '', function (result) {
                feedService._save(result.data, function(){
                    callback(feedService._get());
                });
            });
        }else if(page == 'profile') {
            var profileSource = profileService._get();
            $http._ajax('get', '/ajax/feeds/profile/user/' + profileSource.uid, '', function (result) {
                feedService._save(result.data, function(){
                    callback(feedService._get());
                });
            });
        }
    };
    feedService._loadItem = function(feedItemID, callback)
    {
      if(typeof feedItemID !== 'undefined')
      {
          $http._ajax('get', '/ajax/feeds/item/'+feedItemID, '', function (response) {
                  callback(response.data.feedItem);
          });
      }
    };
    feedService._removeComment = function(commentId, feedIndex, commentIndex, callback)
    {
        var data = {id : commentId};
        $http._ajax('delete', '/ajax/feeds/comments/', data, function(response){
            if(typeof response.data.feeds.success !== 'undefined' && response.data.feeds.success === true)
                feedSource[feedIndex].comments.splice(commentIndex,1);
            callback(response.feeds);
        });
    };
    /* Post */
    feedService._addMedia = function(){

    };
    feedService._removeMedia = function(){

    };

    feedService._submit = function(feedPost, callback){
        $http._ajax('post', '/ajax/feeds', feedPost, function (response) {
            callback(response.data);
        });
    };

    /* Feed Item Popover */
    feedService._selectFeedItemId = 0;
    feedService._setSelectFeedItemId = function(feedItemId){
        if(typeof feedItemId === 'undefined')
        return;
        feedService._selectFeedItemId = feedItemId;
    };

    feedService._getSelectFeedItemId = function(){
        return feedService._selectFeedItemId;
    };

    /* Remove Feed Item */
    feedService._removeFeedItem = function(){
        var deleteFeedItemData = {'id' : feedService._selectFeedItemId};
        $http._ajax('delete', '/ajax/feeds', deleteFeedItemData, function(response){
            console.info(response.data);
            var data = response.data;
            if(typeof data.alerts !== "undefined")
                $alert({content:data.alerts.message, type:data.alerts.type, show:true, placement:'top', duration:5});
            if(data.feeds.success === true)
            {
                console.info("successful to remove");
                console.info(feedSource);
                console.warn("select feed item = "+feedService._selectFeedIndex);
                feedSource.splice(feedService._selectFeedIndex, 1);
            }
        });
    };

    feedService._selectFeedIndex = 0;

    feedService._setSelectFeedIndex = function(index){
      feedService._selectFeedIndex = index;
    };

    feedService._getSelectFeedIndex = function()
    {
      return feedService._selectFeedIndex;
    };

    feedService._editPrivacy = function()
    {

    };
    return feedService;
}]);