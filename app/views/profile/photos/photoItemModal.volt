<div class="modal" tabindex="-1" role="dialog" profile-photo-item-ctrl>
    <div class="photo-dialog" id="photo-dialog">
        <div class="photo-viewer" photo-viewer>
            <div class="previous-photo-item noselect"><span class="previous-arrow noselect"> < </span></div>
            <img ng-src="{{ 'http://{{ mc.photoItem.photo.webFileLocation }}' }}" class="photo noselect" id="photo-item">

            <div class="next-photo-item noselect"><span class="next-arrow noselect"> > </span></div>
        </div>
        <div perfect-scrollbar class="photo-details" wheel-propagation="true" wheel-speed="10" min-scrollbar-length="20">
            <div class="edit-tools">
                <a href="#" edit-photo-button="">Edit Photo</a> <a href="#" delete-photo-button>Delete Photo</a></div>
            <div class="details-container">
                <div class="header">
                    <div class="avatar">
                        <a ng-href="/profile/{{ '{{ mc.photoItem.uid.username || mc.photoItem.uid.uid }}/' }}" target="_self"><img class="img-circle" ng-src="{{ "{{ mc.photoItem.uid.avatar }}" }}"></a>
                    </div>
                    <div class="name">
                        <a ng-href="/profile/{{ '{{ mc.photoItem.uid.username || mc.photoItem.uid.uid }}/' }}" target="_self"><span ng-bind="mc.photoItem.uid.name"></span></a>
                    </div>
                </div>
                <div class="caption" ng-bind-html="mc.photoItem.caption | emoji"></div>
            </div>
            <div class="comment-container">
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
                        <div class="body content" contenteditable="true" ng-model="photoCommentModel" id="photoCommentModel" photo-comment-input></div>
                    </div>
                    <div class="footer">
                        <div class="post-btn text-right">
                            <a href="#" class="btn btn-default-outline btn-lg" photo-comment-button data-photo-id="{{ '{{ mc.photoItem._id.$id }}' }}" id="comment-submit">Comment</a>
                        </div>
                        <div class="clear-both"></div>
                    </div>
                </div>
                <div class="item" ng-repeat="commentItem in mc.photoItem.comments">
                    <div class="left">
                        <div class="header">
                            <div class="avatar">
                                <a ng-href="/profile/{{ '{{ commentItem.uid.username || commentItem.uid.uid }}/' }}" target="_self"><img class="img-circle" ng-src="{{ "{{ commentItem.uid.avatar }}" }}"></a>
                            </div>
                        </div>
                    </div>
                    <div class="right"
                    <div class="header">
                        <div class="name">
                            <a ng-href="/profile/{{ '{{ commentItem.uid.username || commentItem.uid.uid }}/' }}" target="_self"><span ng-bind="commentItem.uid.name"></span></a>
                        </div>
                    </div>
                    <div ng-bind-html="commentItem.comment | emoji" class="comment"></div>
                    <div><a href="#" delete-photo-comment-button data-comment-id="{{ '{{ commentItem._id.$id }}' }}" data-comment-index="{{ '{{ $index }}' }}">Delete</a></div>
                    <div ng-bind="commentItem.ts.sec*1000 | date:'MMMM d \'at\' h:mma'">
                    <div class="clear-both"></div>
                </div>

            </div>

        </div>
    </div>
</div>
</div>
