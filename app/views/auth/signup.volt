<div class="signup-wall">
<div class="signup-panel" ng-controller="signupController as signup">
    <div class="logo"><img src="/img/logo.png"></div>
    <div id="social-links">
        <ul>
            <li>
                <a href="/login?provider=Facebook"
                   class="afacebook">Facebook</a>
            </li>
            <li>
                <a href="/login?provider=Twitter"
                   class="atwitter">Twitter</a>
            </li>
            <li>
                <a href="/login?provider=Google"
                   class="agoogleplus">Google+</a>
            </li>
        </ul>
    </div>
    <div class="separate"></div>
    <div class="signup-form">
        <div class="title">Sign up</div>
        <form name="signupform" novalidate class="css-form" ng-submit="signupform.$valid && recovery.send()">
            {{ content() }}
            <div id="alertMessage"></div>
            <div class="form-group">
                <input type="email" class="form-control" id="emailAddress" placeholder="Email" ng-model="user.email"
                       required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" placeholder="Password"
                       ng-model="user.password" required>
            </div>
            <button type="submit" class="btn btn-lg btn-default-outline" ng-click="signup.signup();" ng-disabled="signupform.$invalid">
                Sign up
            </button>
            <a href="/login" class="forgot-password">Log in</a>
        </form>
    </div>
</div>
</div>