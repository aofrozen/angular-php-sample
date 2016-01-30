angular.module("socialSample.feedDirective", [])
    .directive('feedMediaUrlInput', ['ajaxService', 'feedService', '$alert', '$sce', function ($http, feedService, $alert, $sce) {
        var source = {};
        var url = '';
        var embedQuery = function (url, callback) {
            if (typeof url === 'undefined' || url === null) {
                callback(false);
                return;
            }
            source = {'url': url};
            $http._ajax('post', '/ajax/embed/', source, function (response) {
                callback(response.data);
            });
        };
        var _setMediaData = function (scope, source) {
            if (typeof source.embed.url !== 'undefined') {
                feedService._saveMedia(source.embed);
                scope.feedMediaDataShow = true;
                scope.feedMediaTitle = source.embed.title;
                scope.feedMediaUrl = source.embed.url;
                scope.feedMediaCaption = source.embed.description;
                scope.feedMediaHTML = $sce.trustAsHtml(source.embed.html);
                scope.feedMediaThumbnailUrl = source.embed.thumbnail_url;
                scope.feedMediaType = source.embed.type;
                console.info('verifying....');
                console.info(feedService._getMedia());
            }
        };
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('paste', function (event) {
                    scope.feedMediaUrlDisabled = true;
                    setTimeout(function () {
                        url = element.val();
                        embedQuery(url, function (response) {
                            if (typeof response.alerts !== 'undefined')
                                $alert({
                                    title: response.alerts.title,
                                    content: response.alerts.message,
                                    type: response.alerts.type,
                                    show: true,
                                    placement: 'top',
                                    duration: 5
                                });
                            scope.feedMediaUrlDisabled = false;
                            _setMediaData(scope, response);
                            setTimeout(function () {
                                scope.$apply();
                            }, 1000);
                        });
                    }, 5);
                });
                element.bind('keydown', function (event) {
                    if (event.which === 13) {
                        scope.feedMediaUrlDisabled = true;
                        url = element.val();
                        embedQuery(url, function (response) {
                            scope.feedMediaUrlDisabled = false;
                            if (typeof response.alerts !== 'undefined')
                                $alert({
                                    title: response.alerts.title,
                                    content: response.alerts.message,
                                    type: response.alerts.type,
                                    show: true,
                                    placement: 'top',
                                    duration: 5
                                });
                            _setMediaData(scope, response);
                            scope.$apply();
                        });
                    }
                });
            }
        };
    }])
    .directive('quickFeedCommentInput', ['feedService', '$alert', 'userService', function(feedService, $alert, userService){
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                element.bind('keydown', function(event){
                    if(event.which == 13 && element.val() !== '')
                    {
                        var feedId = attrs.feedId;
                        var commentPost = {"id" : feedId, "comment" : element.val()};
                        feedService._postComment(feedId, commentPost, function(response){
                                var userData = userService._get();
                                if(typeof response.feeds.items !== 'undefined')
                                commentPost = {"_id": {"$id" : response.feeds.items.comments._id.$id}, "ts": response.feeds.items.comments.ts, "comment" : response.feeds.items.comments.comment, "uid" : userData};
                                feedService._pushComment(attrs.feedIndex, commentPost);
                                element.val('');
                                if(typeof response.alerts !== 'undefined')
                                {
                                    $alert({
                                        title: response.alerts.title,
                                        content: response.alerts.message,
                                        type: response.alerts.type,
                                        show: true,
                                        placement: 'top',
                                        duration: 5
                                    });
                                }
                        });
                    }
                });
            }
        };
    }])
    .directive('feedCommentInput', ['feedService', '$alert', 'userService', function(feedService, $alert, userService)
    {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                element.bind('keydown', function(event){
                    if(event.which == 13 && element.val() !== '')
                    {
                        console.info('entered!');
                        var feedId = attrs.feedId;
                        var commentPost = {"id" : feedId, "comment" : element.val()};
                        feedService._postComment(feedId, commentPost, function(response){
                            var userData = userService._get();
                            if(typeof response.feeds.items !== 'undefined')
                                commentPost = {"id" : feedId, "ts": response.feeds.items.comments.ts, "comment" : response.feeds.items.comments.comment, "uid" : userData};
                            feedService._pushComment(attrs.feedIndex, commentPost);
                            element.val('');
                            if(typeof response.alerts !== 'undefined')
                            {
                                $alert({
                                    title: response.alerts.title,
                                    content: response.alerts.message,
                                    type: response.alerts.type,
                                    show: true,
                                    placement: 'top',
                                    duration: 5
                                });
                            }
                        });
                    }
                })
            }
        }
    }])
    .directive('feedMediaButton', ['ajaxService', 'feedService', function ($http, feedService) {

        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                scope.feedMediaUrlShow = false;
                element.bind('mousedown', function () {
                    if (scope.feedMediaUrlShow === false) {
                        scope.$apply(function () {
                            scope.feedMediaUrlShow = true;
                            scope.mediaurl = '';
                        });
                    } else {
                        scope.$apply(function () {
                            scope.feedMediaUrlShow = false;
                        });
                    }
                });
            }
        };
    }])
    .directive('feedPostButton', ['ajaxService', 'feedService', function ($http, feedService) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('mousedown', function () {
                    if (scope.feedPostModel === '')
                        return;
                    var feedMediaSource = feedService._getMedia();
                    var feedPost = {
                        'post': scope.feedPostModel,
                        'media': {
                            'url': feedMediaSource.url,
                            'title': feedMediaSource.title,
                            'caption': feedMediaSource.description,
                            'thumbnailUrl': feedMediaSource.thumbnail_url,
                            'html': feedMediaSource.html,
                            'type': feedMediaSource.type
                        },
                        'type': 'feed'
                    };
                    feedService._submit(feedPost, function (response) {
                        var feedItemID = 0,
                            feedItemTSSec = 0;
                        if (typeof response.feeds.items._id !== 'undefined')
                            feedItemID = response.feeds.items._id.$id;
                        if (typeof response.feeds.items.ts !== 'undefined')
                            feedItemTSSec = response.feeds.items.ts.sec;
                        var feedPostItem = {
                            '_id': {
                                '$id': feedItemID
                            },
                            'ts': {
                                'sec': feedItemTSSec
                            },
                            'post': scope.feedPostModel,
                            'media': {
                                'url': feedMediaSource.url,
                                'title': feedMediaSource.title,
                                'caption': feedMediaSource.description,
                                'thumbnailUrl': feedMediaSource.thumbnail_url,
                                'html': feedMediaSource.html,
                                'type': feedMediaSource.type
                            },
                            'type': 'feed',
                            'uid' : {
                                'avatar': scope.mc.user.avatar,
                                'username': scope.mc.user.username,
                                'name': scope.mc.user.name
                            },
                            'comments' : []
                        };
                        scope.mc.feeds.unshift(feedPostItem); //need to change to feedService [test]
                        feedService._clearMedia();
                        scope.feedPostModel = '';
                        scope.feedMediaDataShow = false;
                        scope.feedMediaUrlShow = false;
                    });
                });
            }
        };

    }])
    .directive('feedItemOptionsPopover', ['$popover', 'feedService', function($popover, feedService){
        return {
            restrict: 'A',
            link: function(scope, element, attrs){
                var popover = $popover(element, {
                    contentTemplate: '/view/feed-item-popover',
                    html: true,
                    trigger: 'manual',
                    autoClose: true,
                    scope: scope,
                    placement: 'bottom',
                    animation: ''
                });
                element.bind('click', function(){
                    feedService._setSelectFeedItemId(attrs.feedItemId);
                    feedService._setSelectFeedIndex(attrs.feedItemIndex);
                    popover.show();
                });
            }
        };
    }])
    .directive('feedItemPopoverCtrl', [function(){
        return {
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                console.info('feed item popover controller!');
            }
        };
    }])
    .directive('deleteFeedComment', ['feedService', function(feedService){
        return{
            restrict: 'A',
            link: function(scope, element, attrs){
            element.bind('click', function(e){
                if(typeof attrs.deleteFeedComment !== 'undefined')
                feedService._removeComment(attrs.feedCommentId, attrs.feedIndex, attrs.feedCommentIndex, function(response){
                });

            });
        }
        };
    }])
    .directive('deleteFeedItem', ['feedService', function(feedService){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                element.bind('click', function(e){
                    console.info('delete feed item ' + feedService._getSelectFeedItemId());
                    feedService._removeFeedItem();
                });
            }
        };
    }])
    .directive('editFeedItemPost', ['feedService', function(feedService){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                element.bind('click', function(e){

                    console.info('edit feed item post');
                });
            }
        };
    }])
    .directive('unfollowFeedItemUser', ['feedService', function(feedService){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                element.bind('click', function(e){
                    console.info('unfollow feed item user');
                    feedService._unfollowUser();
                });
            }
        };
    }])
    .directive('feedItemPrivacyModalCtrl', [function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                console.info('feed item privacy controller');
            }
        }
    }])
    .directive('editFeedItemPrivacy', ['$modal', 'feedService', function($modal, feedService){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                element.bind('click', function(e){
                    console.info('edit feed item privacy');
                    /* Start modal */
                    scope.feedItemPrivacyModal = $modal({template: '/view/feed-item-privacy/', show: true, backdropAnimation: 'modal-backdrop', animation: ''});
                });
            }
        };
    }]);