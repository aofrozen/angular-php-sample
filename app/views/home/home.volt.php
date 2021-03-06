<?php if ($ngView != 'false') { ?>
    <?php echo $this->navigation->top(); ?>
    <div ui-view="top"></div>
<?php } else { ?>
    <div ng-controller="homeController as home" class="home">
        <div class="wall" ng-style="{'background-image':'url('+ mc.home.wall+')'}">
            <div class="wall-upload-button">
                <div class="fileUpload">
                    <span class="glyphicon glyphicon-camera" aria-hidden="true"></span>
                    <input href="#" class="btn btn-lg btn-default-outline upload" class="upload" type="file"
                           nv-file-select="" accept=".jpg,.png,.jpeg" uploader="home.wallUploader">
                </div>
            </div>
        </div>
        <div class="follow-friends" perfect-scrollbar wheel-propagation="true" wheel-speed="10"
             min-scrollbar-length="20">
            <ul>
                <li><span class="heading">Following Friends</span></li>
                <li><a href="#" follow-friend>Follow +</a></li>
                <li ng-repeat="followFriend in mc.user.follows">
                    <a href="#" select-feed-follow-id="<?php echo '{{ followFriend.followUid.followUid }}'; ?>"
                       ng-bind="followFriend.followUid.name">Justin Lee</a>
                </li>
            </ul>
        </div>
        <div class="feed-container">
            <div class="home-feed">
                <div class="clear"></div>
                <div class="filters"><a href="#" class="btn btn-default-outline">All</a> <a href="#"
                                                                                            class="btn btn-default-outline">Photos</a>
                    <a href="#" class="btn btn-default-outline">Journals</a> <a href="#"
                                                                                class="btn btn-default-outline">Likes</a>
                    <a href="#" class="btn btn-default-outline">Comments</a></div>
                <div class="post">
                    <div class="header">
                        <div class="avatar">
                            <img class="img-circle" ng-src="<?php echo '{{ mc.user.avatar }}'; ?>">
                        </div>
                        <div class="name">
                            <span ng-bind="mc.user.name"></span>
                        </div>
                    </div>
                    <div class="clear-both"></div>
                    <div class="body content" contenteditable="true" ng-model="feedPostModel"></div>
                    <div class="footer">
                        <div class="media-preview" ng-if="feedMediaDataShow">
                            <div class="embed-responsive embed-responsive-16by9" ng-show="feedMediaType == 'video'">
                                <embed-video href="<?php echo '{{ feedMediaUrl }}'; ?>"></embed-video>
                            </div>
                            <div class="thumbnail">
                                <a ng-href="<?php echo '{{ feedMediaUrl }}'; ?>"><img
                                            ng-src="<?php echo '{{ feedMediaThumbnailUrl }}'; ?>"
                                            ng-hide="feedMediaType == 'video'"></a>
                            </div>
                            <a ng-href="<?php echo '{{ feedMediaUrl }}'; ?>" class="title" ng-bind="feedMediaTitle"></a><br>
                            <a ng-href="<?php echo '{{ feedMediaUrl }}'; ?>" class="caption" ng-bind="feedMediaCaption"
                               ng-hide="feedMediaType == 'video'"></a>
                        </div>
                        <div class="media form-group">
                            <input type="text" value="" placeholder="Ex: https://www.youtube.com/watch?v=3yCSlq9fOD4"
                                   class="form-control" ng-model="mediaurl" ng-disabled="feedMediaUrlDisabled"
                                   feed-media-url-input ng-show="feedMediaUrlShow">
                        </div>
                        <div class="insert-options col-sm-6 text-left">
                            <a href="#" class="btn btn-default-outline btn-lg">
                                <span class="glyphicon glyphicon-camera"></span>
                            </a>
                            <a href="#" class="btn btn-default-outline btn-lg" feed-media-button>
                                <span class="glyphicon glyphicon-globe"></span>
                            </a>
                        </div>
                        <div class="post-btn col-sm-6 text-right">
                            <a href="#" class="btn btn-default-outline btn-lg" feed-post-button>Post</a>
                        </div>
                        <div class="clear-both"></div>
                    </div>
                </div>

                <div class="feed-animate-repeat" ng-repeat="feed in mc.feeds">
                    <div class="item">
                        <div class="close"><span class="glyphicon glyphicon-chevron-down" feed-item-options-popover
                                                 data-feed-item-id="<?php echo '{{ feed._id.$id }}'; ?>"
                                                 data-feed-item-index="<?php echo '{{ $index }}'; ?>"></span></div>
                        <div class="header">
                            <a href="/profile/<?php echo '{{ feed.uid.username || feed.uid.uid }}'; ?>">
                                <div class="avatar">
                                    <img class="img-circle" ng-src="<?php echo '{{ feed.uid.avatar }}'; ?>">
                                </div>
                            </a>
                            <a href="/profile/<?php echo '{{ feed.uid.username || feed.uid.uid }}'; ?>">
                                <div class="name"><span ng-bind="feed.uid.name"></span></div>
                            </a>

                            <div class="date" ng-bind="feed.ts.sec*1000 | date:'MMMM d \'at\' h:mma'">
                            </div>
                        </div>
                        <div class="body" ng-bind-html="feed.post| emoji"></div>
                        <div class="media-item">
                            <div ng-show="feed.media.type == 'video'">
                                <div class="thumbnail">
                                    <a ng-href="/feeds/item/<?php echo '{{ feed._id.$id }}'; ?>/video"><img
                                                ng-src="<?php echo '{{ feed.media.thumbnailUrl }}'; ?>"></a>
                                </div>
                                <a ng-href="/feeds/item/<?php echo '{{ feed._id.$id }}'; ?>/video" class="title"
                                   ng-bind="feed.media.title"></a><br>
                            </div>
                            <div ng-show="feed.media.type == 'link'">
                                <div class="thumbnail">
                                    <a ng-href="<?php echo '{{ feed.media.url }}'; ?>" target="_blank"><img
                                                ng-src="<?php echo '{{ feed.media.thumbnailUrl }}'; ?>"></a>
                                </div>
                                <a ng-href="<?php echo '{{ feed.media.url }}'; ?>" class="title"
                                   ng-bind="feed.media.title"></a><br>
                                <a ng-href="<?php echo '{{ feed.media.url }}'; ?>" class="caption"
                                   ng-bind="feed.media.caption"></a>
                            </div>
                        </div>
                        <div class="footer">
                            <a href="#" class="like-icon">0
                                <span class="glyphicon glyphicon-heart"
                                      feed-like-button="<?php echo '{{ feed._id.$id }}'; ?>"></span></a>
                        </div>
                        <div class="comments">
                            <div class="comment-post">
                                <input class="form-control" type="text" placeholder="Write a comment..."
                                       quick-feed-comment-input data-feed-id="<?php echo '{{ feed._id.$id }}'; ?>"
                                       data-feed-index="<?php echo '{{ $index }}'; ?>">
                            </div>

                            <div class="comment-items" ng-repeat="comment in feed.comments">
                                <div class="user">
                                    <div class="avatar">
                                        <a ng-href="/profile/<?php echo '{{ comment.uid.username || comment.uid.uid }}'; ?>"><img
                                                    class="img-circle" ng-src="<?php echo '{{ comment.uid.avatar }}'; ?>"></a>
                                    </div>
                                    <a ng-href="/profile/<?php echo '{{ comment.uid.username || comment.uid.uid }}'; ?>">
                                        <div ng-bind="comment.uid.name"></div>
                                    </a></div>
                                <div class="body">
                                    <div class="comment" ng-bind-html="comment.comment | emoji"></div>
                                    <div class="footer"><span
                                                ng-bind="comment.ts.sec*1000 | date:'MMMM d \'at\' h:mma'"></span> <a
                                                href="#" delete-feed-comment
                                                data-feed-comment-id = "<?php echo '{{ comment._id.$id  }}'; ?>"
                                                data-feed-id="<?php echo '{{ feed._id.$id }}'; ?>"
                                                data-feed-index="<?php echo '{{ $parent.$index }}'; ?>"
                                                data-feed-comment-index="<?php echo '{{ $index }}'; ?>">Delete</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php echo $this->getContent(); ?>