<div class="login-recovery-wall">
<div class="login-recovery-panel" ng-controller="loginRecoveryController as recovery">
    <div class="logo"><img src="/img/logo.png"></div>
    <div class="login-recovery-form">
        <div class="title">Password Recovery</div>
        <form name="loginrecoveryform" class="css-form" novalidate
              ng-submit="loginrecoveryform.$valid && recovery.send()">
            <div id="alertMessage"></div>
            <div class="form-group">
                <input type="email" class="form-control" id="emailAddress" placeholder="Email" ng-model="user.email"
                       required=""/>
            </div>
            <input type="submit" class="btn btn-lg btn-default-outline" value="Send" ng-disabled="loginrecoveryform.$invalid"/>
        </form>
    </div>
</div>
</div>