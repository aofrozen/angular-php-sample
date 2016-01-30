<div class="modal" tabindex="-2" role="dialog" edit-photo-ctrl>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label for="photoCaption">Photo Caption</label>
                    <input id="photoCaption" type="text" class="form-control" ng-model="photoCaptionModel" placeholder="Write caption...">
                </div>
                <div class="form-group">
                    <label for="photoTags">Photo Albuums</label>
                    <tags-input ng-model="tags" placeholder="Add album">
                                                <auto-complete source="loadTags($query)"></auto-complete>
                                            </tags-input>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" ng-click="$hide()">Cancel</a>
                <a href="#" class="btn btn-success" save-photo-change-button ng-click="$hide()" id="save-photo-change-button">Save</a>
            </div>
        </div>
    </div>
</div>