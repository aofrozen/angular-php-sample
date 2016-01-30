<?php if ($ngView != 'false') { ?>
    <?php echo $this->navigation->top(); ?>
    <div ui-view="top"></div>
<?php } else { ?>
    <div class="settings" ng-controller="settingsController">
        <init></init>
        <div class="wall"
             ng-style="{'background-image':'url('+mc.home.homeWall+')'}">
        </div>
        <div class="content">
            <div class="sidebar">
                Contact & Account
            </div>
            <div class="body">
                <form>
                    <h3>Contact & Account</h3>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" ng-model="settings.auth.email">
                    </div>
                    <div class="form-group">
                        <label for="mobile-numbers">Mobile Numbers</label>
                        <input type="text" class="form-control" id="mobile-numbers"
                               ng-model="settings.profile.mobileNumbers">
                    </div>
                    <h3>Profile</h3>

                    <div class="form-group">
                        <label for="name">First Name</label>
                        <input type="text" class="form-control" id="name" ng-model="settings.profile.fName">
                    </div>
                    <div class="form-group">
                        <label for="name">Last Name</label>
                        <input type="text" class="form-control" id="name" ng-model="settings.profile.lName">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="email" ng-model="settings.profile.username">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="email" ng-model="settings.profile.description">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" ng-model="settings.profile.gender"
                                ng-options="opt as opt.label for opt in genderOptions"></select>
                    </div>

                    <div class="form-group">
                        <label for="gender">Race</label>
                        <select class="form-control" ng-model="settings.profile.race"
                                ng-options="opt as opt.label for opt in raceOptions"></select>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="email" ng-model="settings.profile.country">
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="email" ng-model="settings.profile.state">
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="email" ng-model="settings.profile.city">
                    </div>
                    <h3>Privacy</h3>

                    <div class="form-group">
                        <div class="form-group">
                            <label for="wcmy">Who can message you?</label>
                            <select class="form-control" ng-model="settings.privacy.contact.message_filter"
                                    ng-options="opt as opt.label for opt in wcmyOptions"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="wcryp">Who can read your profile?</label>
                            <select class="form-control" ng-model="settings.privacy.profile.read_profile_filter"
                                    ng-options="opt as opt.label for opt in wcrypOptions"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="wcrypn">Who can read your phone numbers?</label>
                            <select class="form-control" ng-model="settings.privacy.profile.read_phone_filter"
                                    ng-options="opt as opt.label for opt in wcrypnOptions"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="wcryea">Who can read your email address?</label>
                            <select class="form-control" ng-model="settings.privacy.profile.read_email_filter"
                                    ng-options="opt as opt.label for opt in wcryeaOptions"></select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" settings-submit-button>Save Changes</button>
                </form>
            </div>
        </div>
    </div>
<?php } ?>