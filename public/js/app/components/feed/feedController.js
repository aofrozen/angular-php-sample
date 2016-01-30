angular.module('socialSample.feed', [])
    .controller('profileFeedController', [function () {
        console.info('profileFeedController');
    }])
    .controller('homeFeedController', [function () {
        console.info('homeFeedController');
    }])
    .controller('feedItemController', ['$scope', '$stateParams', 'feedService', function($scope, $stateParams, feedService){
        $scope.mc = {};
        $scope.feedItem = {};
        if(typeof $stateParams.id !== 'undefined'){
            feedService._loadItem($stateParams.id, function(feedItemSource){
                $scope.mc.feedItem = feedItemSource;
            });
        }
    }])
    .controller('feedItemPopoverController', ['$scope', function($scope){
        console.info('feed item popover controller');
    }]);
