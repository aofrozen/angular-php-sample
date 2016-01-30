<div class="about" ng-controller="profileAboutController">
    <div class="card" ng-show="mc.about.mobileNumbers">
        <div class="detail-type"><span class="glyphicon glyphicon-phone"></span></div>
        <div class="detail-value"
             ng-bind="mc.about.mobileNumbers">{{ profileMobileNumbers }}</div>
    </div>
    <div class="card" ng-show="mc.about.email">
        <div class="detail-type"><span class="glyphicon glyphicon-envelope"></span></div>
        <div class="detail-value"
             ng-bind="mc.about.email">{{ profileEmail }}</div>
    </div>
    <div class="card" ng-show="mc.about.birthday">
        <div class="detail-type"><i class="fa fa-birthday-cake"></i></div>
        <div class="detail-value"
             ng-bind="mc.about.birthday">{{ profileBirthday }}</div>
    </div>
    <div class="card" ng-show="mc.about.location">
        <div class="detail-type"><i class="fa fa-map-marker"></i></div>
        <div ng-bind="mc.about.location">{{ profileLocation }}</div>
    </div>
    <div class="card" ng-show="mc.about.gender == 'Male'">
        <div class="detail-type"><i class="fa fa-male"></i></div>
        <div class="detail-value"
             ng-bind="mc.about.gender">{{ profileGender }}</div>
    </div>
    <div class="card" ng-show="mc.about.gender == 'Female'">
        <div class="detail-type"><i class="fa fa-female"></i></div>
        <div class="detail-value"
             ng-bind="mc.about.gender">{{ profileGender }}</div>
    </div>
    <div class="clear-both"></div>
</div>