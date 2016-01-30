{% if ngView != 'false' %}
    {{ this.navigation.top() }}
    {{ content() }}
    <div ui-view="top"></div>
{% else %}
    <div ng-controller="profileController" class="profile">
        <div id="insertEditor" style="position:absolute;z-index:1029;" insert-html-tool
             ng-style="insertEditorStyle"></div>
        <init init-id="{{ pid }}"></init>
        <div class="wall"
             ng-style="{'background-image':'url('+mc.profile.wall+')', 'background-color':'#BBB', 'background-position':mc.profile.wallPosition, 'rotate':mc.profile.wallRotation+'deg'}">
            <div class="avatar">
                <img class="img-circle"
                     ng-src="{{ "{{ mc.profile.avatar }}" }}">

                <div class="avatar-upload-button" ng-show="mc.user.uid == mc.profile.uid">
                    <div class="fileUpload">
                        <span class="glyphicon glyphicon-camera" aria-hidden="true"></span>
                        <input href="#" class="btn btn-lg btn-default-outline upload" class="upload" type="file"
                               nv-file-select="" accept=".jpg,.png,.jpeg" uploader="avatarUploader">
                    </div>
                </div>
            </div>
            <div class="wall-upload-button" ng-show="mc.user.uid == mc.profile.uid">
                <div class="wall-settings-button"><a href="#" profile-wall-rotation-toggle><span
                                class="glyphicon glyphicon-refresh"></span></a></div>
                <div class="wall-settings-button"><a href="#" profile-wall-position-toggle><span
                                class="glyphicon glyphicon-resize-vertical"></span></a></div>
                <div class="fileUpload">
                    <span class="glyphicon glyphicon-camera" aria-hidden="true"></span>
                    <input href="#" class="btn btn-lg btn-default-outline upload" class="upload" type="file"
                           nv-file-select="" accept=".jpg,.png,.jpeg" uploader="wallUploader">
                </div>
            </div>
        </div>
        <div class="info">
            <div class="info-container">
                <div class="name text-center" ng-bind="mc.profile.name">
                </div>
                <div class="description text-center" ng-bind="mc.profile.description">
                </div>
                <div class="username text-center">
                    @<span ng-bind="mc.profile.username"></span>
                </div>
                {{ content() }}
                <!--
                <div class="friends text-center">
                    1,000 Friends
                    <div class="list">
                        <div class="row">
                            <div class="friend-avatar col-sm-3"><img src="" class="circle"/></div>
                            <div class="friend-avatar col-sm-3"><img src="" class="circle"/></div>
                            <div class="friend-avatar col-sm-3"><img src="" class="circle"/></div>
                            <div class="friend-avatar col-sm-3"><img src="" class="circle"/></div>
                        </div>
                        <div class="row">
                            <div class="friend-avatar col-sm-3"><img src="" class="circle"/></div>
                            <div class="friend-avatar col-sm-3"><img src="" class="circle"/></div>
                            <div class="friend-avatar col-sm-3"><img src="" class="circle"/></div>
                            <div class="friend-avatar col-sm-3"><img src="" class="circle"/></div>
                        </div>
                    </div>
                </div>
                !-->
                <div class="contacts text-center">
                    <a href="#" class="btn btn-lg btn-default-outline" add-friend-button="{{ pid }}"
                       ng-bind="mc.friends.addFriendButtonName || 'loading'">Add Friend</a>
                    <a ng-href="/messages/{{ pid }}" class="btn btn-lg btn-default-outline">Message</a>
                </div>
                <div class="options text-center">
                    <a ui-sref="profile" class="btn btn-lg btn-default-blank"
                       ng-class="{'active' : (profileSelectedTab == 'timeline')}">Timeline</a>
                    <a ui-sref=".friends" class="btn btn-lg btn-default-blank"
                       ng-class="{'active' : (profileSelectedTab == 'friends')}">{{ '{{ mc.friends.friendCount }}' }}
                        Friends</a>
                    <a ui-sref=".about" class="btn btn-lg btn-default-blank"
                       ng-class="{'active' : (profileSelectedTab == 'about')}">About</a>
                    <a ui-sref="profile.photos" class="btn btn-lg btn-default-blank"
                       ng-class="{'active' : (profileSelectedTab == 'photos')}">Photos</a>
                </div>
            </div>
        </div>
        <div ui-view="profile-content" id="profile-content">
            <div class="profile-feed" ng-if="(profileSelectedTab == 'timeline')">
                <div class="post">
                    <div class="header">
                        <div class="avatar">
                            <img class="img-circle" ng-src="{{ "{{ mc.user.avatar }}" }}">
                        </div>
                        <div class="name">
                            <span ng-bind="mc.user.name"></span>
                        </div>
                    </div>
                    <div class="clear-both"></div>
                    <div>
                        <div class="body content" contenteditable="true" ng-model="feedPostModel"></div>
                    </div>
                    <div class="footer">
                        <div class="media form-group">
                            <div class="media-preview" ng-show="feedMediaDataShow">
                                <div class="embed-responsive embed-responsive-16by9" ng-show="feedMediaType == 'video'">
                                    <embed-video href="{{ '{{ feedMediaUrl }}' }}"></embed-video>
                                </div>
                                <div class="thumbnail"><a ng-href="{{ '{{ feedMediaUrl }}' }}"><img
                                                ng-src="{{ '{{ feedMediaThumbnailUrl }}' }}"
                                                ng-hide="feedMediaType == 'video'"></a></div>
                                <a ng-href="{{ '{{ feedMediaUrl }}' }}" class="title" ng-bind="feedMediaTitle"></a><br>
                                <a ng-href="{{ '{{ feedMediaUrl }}' }}" class="caption" ng-bind="feedMediaCaption"
                                   ng-hide="feedMediaType == 'video'"></a>
                            </div>
                            <input type="text" value="" placeholder="Ex: https://www.youtube.com/watch?v=3yCSlq9fOD4"
                                   class="form-control" ng-model="mediaurl" ng-disabled="feedMediaUrlDisabled"
                                   feed-media-url-input ng-show="feedMediaUrlShow">
                        </div>
                        <div class="insert-options col-sm-6 text-left">
                            <a href="#" class="btn btn-default-outline btn-lg">
                                <span class="glyphicon glyphicon-camera"></span>
                            </a>
                            <a href="#" class="btn btn-default-outline btn-lg" feed-media-button>
                                <span class="glyphicon glyphicon-globe"></span></a>
                        </div>
                        <div class="post-btn col-sm-6 text-right">
                            <a href="#" class="btn btn-default-outline btn-lg" feed-post-button>Post</a></div>
                        <div class="clear-both"></div>
                    </div>
                </div>
                <div class="feed-animate-repeat" ng-repeat="feed in mc.feeds">
                    <div class="item">
                        <div class="close"><span class="glyphicon glyphicon-chevron-down" feed-item-options-popover
                                                 data-feed-item-id="{{ "{{ feed._id.$id }}" }}"
                                                 data-feed-item-index="{{ '{{ $index }}' }}"></span></div>
                        <div class="header">
                            <a href="/profile/{{ '{{ feed.uid.username || feed.uid.uid }}' }}">
                                <div class="avatar">
                                    <img class="img-circle" ng-src="{{ "{{ feed.uid.avatar }}" }}">
                                </div>
                            </a>
                            <a href="/profile/{{ '{{ feed.uid.username || feed.uid.uid }}' }}">
                                <div class="name"><span ng-bind="feed.uid.name"></span></span>
                                </div>
                            </a>

                            <div class="date" ng-bind="feed.ts.sec*1000 | date:'MMMM d \'at\' h:mma'">
                            </div>
                        </div>
                        <div class="body" ng-bind-html="feed.post | emoji">
                        </div>
                        <div class="media-item">
                            <div ng-show="feed.media.type == 'video'">
                                <div class="thumbnail" ng-show="feed.media.thumbnailUrl"><a
                                            ng-href="/feeds/item/{{ '{{ feed._id.$id }}' }}/video"><img
                                                ng-src="{{ '{{ feed.media.thumbnailUrl }}' }}"></a></div>
                                <a ng-href="/feeds/item/{{ '{{ feed._id.$id }}' }}/video" class="title"
                                   ng-bind="feed.media.title"></a><br>
                            </div>
                            <div ng-show="feed.media.type != 'video'">
                                <div class="thumbnail" ng-show="feed.media.thumbnailUrl"><a
                                            ng-href="{{ '{{ feed.media.url }}' }}" target="_blank"><img
                                                ng-src="{{ '{{ feed.media.thumbnailUrl }}' }}"></a></div>
                                <a ng-href="{{ '{{ feed.media.url }}' }}" class="title"
                                   ng-bind="feed.media.title"></a><br>
                                <a ng-href="{{ '{{ feed.media.url }}' }}" class="caption"
                                   ng-bind="feed.media.caption"></a>
                            </div>
                        </div>
                        <div class="footer">
                            <a href="#" class="like-icon">0 <span class="glyphicon glyphicon-heart"
                                                                  feed-like-button="{{ '{{ feed._id.$id }}' }}"></span></a>
                        </div>
                        <div class="comments">
                            <div class="comment-post">
                                <input class="form-control" type="text" placeholder="Write a comment..."
                                       quick-feed-comment-input
                                       data-feed-id={{ '{{ feed._id.$id }}' }} data-feed-index="{{ '{{ $index }}' }}">
                            </div>
                            <div class="comment-items" ng-repeat="comment in feed.comments">
                                <div class="user">
                                    <div class="avatar">
                                        <a ng-href="/profile/{{ '{{ comment.uid.username || comment.uid.uid }}' }}"><img
                                                    class="img-circle" ng-src="{{ '{{ comment.uid.avatar }}' }}"></a>
                                    </div>
                                    <a ng-href="/profile/{{ '{{ comment.uid.username || comment.uid.uid }}' }}">
                                        <div ng-bind="comment.uid.name"></div>
                                    </a></div>
                                <div class="body">
                                    <div class="comment" ng-bind-html="comment.comment | emoji"></div>
                                    <div class="footer"><span
                                                ng-bind="comment.ts.sec*1000 | date:'MMMM d \'at\' h:mma'"></span> <a
                                                href="#" delete-feed-comment
                                                data-feed-comment-id = "{{ '{{ comment._id.$id  }}' }}"
                                                data-feed-id="{{ '{{ feed._id.$id }}' }}"
                                                data-feed-index="{{ '{{ $parent.$index }}' }}"
                                                data-feed-comment-index="{{ '{{ $index }}' }}">Delete</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-options">
        <div class="options">
				<span class="no-overflow">
					<span class="lengthen ui-inputs">
						<button class="url useicons">URL</button>
						<input class="url-input" type="text" placeholder="Type or Paste URL here"/>
						<button class="bold">b</button>
						<button class="italic">i</button>
						<button class="quote">&rdquo;</button>
					</span>
				</span>
        </div>
    </div>
{% endif %}
