{% if ngView != 'false' %}
    {{ this.navigation.top() }}
    <div ng-view></div>
{% else %}
    <div class="aside bs-docs-aside notifications" tabindex="-1" role="dialog" ng-controller="notificationController">
        <div class="aside-dialog">
            <div class="aside-content">
                <div class="aside-body bs-sidebar" style="float:left">
                    <div class="btn-group btn-group-justified notification-options" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default">All</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default">Friend Requests</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default">Comments</button>
                        </div>
                    </div>
                    <div ng-repeat="notification in mc.notifications">
                        <div class="item" ng-show="notification.type == 'friend.request'">
                            <div class="avatar"><a ng-href="/profile/{{ "{{ notification.fid }}" }}"><img
                                            class="img-circle" ng-src="{{ "{{ notification.fid.avatar}}" }}"></a></div>
                            <div class="body"><a ng-href="/profile/{{ "{{ notification.fid }}" }}">
                                    <div class="title" ng-bind="{{ "notification.fid.name" }}"></div>
                                </a><div class="options"><button class="btn btn-success"
                                       accept-friend-request-button index="{{ '{{ $index }}' }}" ng-disabled="{{ 'notification.disabled' }}">Accept</button>
                                <button class="btn btn-danger"
                                   reject-friend-request-button
                                   index="{{ '{{ $index }}' }}" ng-disabled="{{ 'notification.disabled' }}">Reject</button></div></div>
                        </div>
                        <div class="item" ng-show="notification.type == 'photo.comment'">
                            <div class="avatar"><img class="img-circle"
                                                     src="https://scontent-sjc.xx.fbcdn.net/hphotos-xpf1/t31.0-8/1978428_10153113647729502_2939448083042738627_o.jpg">
                            </div>
                            <div class="body">Justin Lee</div>
                        </div>
                        <div class="item" ng-show="notification.type == 'feed.post.comment'">
                            <div class="avatar"><img class="img-circle"
                                                     src="https://scontent-sjc.xx.fbcdn.net/hphotos-xpf1/t31.0-8/1978428_10153113647729502_2939448083042738627_o.jpg">
                            </div>
                            <div class="body">Justin Lee</div>
                        </div>
                        <div class="item" ng-show="notification.type == 'profile.comment'">
                            <div class="avatar"><img class="img-circle"
                                                     src="https://scontent-sjc.xx.fbcdn.net/hphotos-xpf1/t31.0-8/1978428_10153113647729502_2939448083042738627_o.jpg">
                            </div>
                            <div class="body">Justin Lee</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endif %}