<div ng-controller="profileFriendController" class="friends-list">
    <div ng-repeat="friend in mc.friends.list">
        <div class="item">
            <a ng-href="/profile/{{ '{{ friend.fid.username || friend.fid.fid }}' }}"><div class="avatar"><img class="img-circle" ng-src="{{ '{{ friend.fid.avatar }}' }}"></div>
            <div class="name">{{ '{{ friend.fid.name }}' }}</div>
            <div class="username" ng-show="friend.fid.username">@{{ '{{ friend.fid.username }}' }}</div></a>
            <div class="buttons"><a href="#" class="btn btn-default-outline" add-friend-button="{{ '{{ friend.fid.fid }}' }}" ng-show="{{ ' mc.friends.uid == mc.user.uid' }}">Friends</a> <a ng-href="/messages/{{ '{{ friend.fid.username || friend.fid.fid }}' }}" class="btn btn-default-outline" >Message</a></div>
        </div>
    </div>
</div>