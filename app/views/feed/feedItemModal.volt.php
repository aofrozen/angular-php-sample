<?php if ($ngView != 'false') { ?>
    <?php echo $this->navigation->top(); ?>
    <div ui-view="top"></div>
<?php } else { ?>
    <div class="modal" tabindex="-1" role="dialog" ng-controller="feedItemController">
    <div class="close">X</div>
    <div class="feed-item-dialog">
        <div class="feed-item-content">
            <div class="media">
                <div ng-show="mc.feedItem.media.type == 'video'" class="video-player">
                    <div class="embed-responsive embed-responsive-16by9" ng-show="mc.feedItem.media.type == 'video'">
                        <embed-video href="<?php echo '{{ mc.feedItem.media.url }}'; ?>"></embed-video>
                    </div>
                    <a ng-href="<?php echo '{{ mc.feedItem.data.url }}'; ?>" class="title" ng-bind="mc.feedItem.media.title"></a><br>
                </div>
                <div ng-show="mc.feedItem.media.type != 'video'" class="article-summary">
                    <img class="thumbnail" ng-src="<?php echo '{{ mc.feedItem.media.thumbnailUrl }}'; ?>"><br>
                    <a ng-href="<?php echo '{{ mc.feedItem.media.url }}'; ?>" class="title"
                       ng-bind="mc.feedItem.media.title"></a><br>
                    <a ng-href="<?php echo '{{ mc.feedItem.media.url }}'; ?>" class="caption" ng-bind="mc.feedItem.media.caption"
                       ng-hide="mc.feedItem.media.type == 'video'"></a>
                </div>
            </div>
            <div>
                <div class="clear-both"></div>
            </div>
            <div class="feed-comment-dialog">
                <div class="details">
                    <div class="header">
                        <a href="/profile/<?php echo '{{ mc.feedItem.uid.username || mc.feedItem.uid.uid }}'; ?>">
                            <div class="avatar">
                                <img class="img-circle" ng-src="<?php echo '{{ mc.feedItem.uid.avatar }}'; ?>">
                            </div>
                        </a>
                        <a href="/profile/<?php echo '{{ mc.feedItem.uid.username || mc.feedItem.uid.uid }}'; ?>">
                            <div class="name"><span ng-bind="mc.feedItem.uid.name"></span>
                            </div>
                        </a>

                        <div class="date" ng-bind="mc.feedItem.ts.sec*1000 | date:'MM/dd/yyyy'">
                        </div>
                    </div>

                    <div class="body" ng-bind-html="feed.data.content | emoji"></div>
                </div>
                <input type="text" class="form-control" placeholder="Write a comment..." ng-model="feedComment" feed-comment-input>

                <div class="comment-items" ng-repeat="comment in mc.feedItem.comments">
                    <div ng-bind="comment.comment"></div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>