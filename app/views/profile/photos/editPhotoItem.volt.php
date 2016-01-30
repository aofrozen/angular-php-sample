<div class="modal" tabindex="-2" role="dialog" edit-photo-ctrl>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label for="photoCaption">Photo Caption</label>
                    <input id="photoCaption" type="text" class="form-control" ng-model="photoCaptionModel" placeholder="Write caption...">
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" ng-click="$hide()">Cancel</a>
                <a href="#" class="btn btn-success" save-photo-change-button ng-click="$hide()">Save</a>
            </div>
        </div>
    </div>
</div>