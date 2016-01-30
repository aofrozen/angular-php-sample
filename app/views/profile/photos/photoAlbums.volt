<div ng-controller="profilePhotoAlbumsController" class="photo-items">
    <ul>
        <li ng-repeat="photoAlbum in mc.photoAlbums.items">
            <a ng-href="/profile/{{ '{{  mc.profile.username || mc.profile.uid }}/photos/albums/{{ photoAlbum.tagName }}/' }}"><div class='album-photo'><img ng-src="//{{ '{{ photoAlbum.photo.thumb.webFileLocation }}' }}"/><span ng-bind="photoAlbum.tagName"></span></div></a>
        </li>
    </ul>
    <div class="clear-both"></div>
</div>