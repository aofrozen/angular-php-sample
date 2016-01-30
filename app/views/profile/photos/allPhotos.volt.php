<div ng-controller="profilePhotoController" class="photo-items">
    <div ng-bind="mc.photos.tagName" ng-show="mc.photos.tagName" class="album-name"></div>
    <ul>
        <li ng-repeat="item in mc.photos.items" ng-hide="photoLoading">
            <a ng-href="/profile/<?php echo '{{ mc.profile.username || mc.profile.uid }}/photos/item/{{ item._id.$id }}/'; ?>"><img ng-src="//<?php echo '{{ item.photo.thumb.webFileLocation }}'; ?>"/></a>
        </li>
    </ul>
    <div class="clear-both"></div>
</div>