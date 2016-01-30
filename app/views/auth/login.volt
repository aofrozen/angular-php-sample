<div class="login-wall">
</div>
<div class="login-panel" ng-controller="loginController as login">
    <div class="logo"><img src="/img/logo.png"></div>
    <div id="social-links">
        <ul>
            <li>
                <a target="_self" href="/login?provider=Facebook"
                   class="afacebook">Facebook</a>
            </li>
            <li>
                <a target="_self" href="/login?provider=Twitter"
                   class="atwitter">Twitter</a>
            </li>
            <li>
                <a target="_self" href="/login?provider=Google"
                   class="agoogleplus">Google+</a>
            </li>
        </ul>
    </div>
    <div class="separate"></div>
    <div class="login-form">
        <div class="title">Login</div>
        {{ content() }}
        <form name="loginform" class="css-form" novalidate ng-submit="loginform.$valid && login.login()">
            <div id="alertMessage"></div>
            <div class="form-group">
                <input type="email" class="form-control" id="emailAddress" name="email" placeholder="Email"
                       ng-model="user.email" required="">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                       ng-model="user.password" required>
            </div>
            <button type="submit" class="btn btn-lg btn-default-outline" ng-disabled="loginform.$invalid">Log in</button>
            <a href="/recover" class="forgot-password" target="_self">Forgot Password</a>
        </form>
    </div>
</div>