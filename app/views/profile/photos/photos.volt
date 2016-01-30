<div>
    <div class="photo-container">
        <div class="content">
            <div class="body">
                <div class="photo-nav">
                    <div class="btn-group" role="group">
                        <a ui-sref="profile.photos" class="btn btn-default" all-photos-button="{{ pid }}">All Photos</a>
                        <a ui-sref="profile.photos.albums" class="btn btn-default" albums-button="{{ pid }}">Albums</a>
                    </div>
                    <a class="btn btn-default" ng-show="{{ '{{ pid == mc.user.uid }}' }}" open-photo-upload-btn><span class="glyphicon glyphicon-plus"></span></a>
                </div>
                <div ui-view="photo-content"></div>
            </div>
        </div>
    </div>
</div>