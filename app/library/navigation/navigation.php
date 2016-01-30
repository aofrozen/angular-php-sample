<?php

namespace app\library\navigation;

use app\library\session\userSessions;

class navigation
    {
        public function top()
            {
                $userSession = new userSessions();
                $userID = (int)$userSession->getUserID();
                if ($userID)
                    {
                        /*
                         * Home, Profile, Photo, Notification, Message, Settings
                         */
                        return <<<EOF
            <div class="navbar-fixed-top">
    <nav class="socialSample-navbar" role="navigation" ng-controller="navController">
<ul class="socialSample-navbar-right">
     <li class="nav-item"><a ui-sref=".home" class="home-nav-btn circle"><span class="glyphicon glyphicon-home"></span></span></a></li>
     <li class="nav-item"><a ui-sref=".profile({ uid: (mc.user.username || mc.user.uid) })" class="profile-nav-btn circle"><span class="glyphicon glyphicon-user"></span></a></li>
     <li class="nav-item"><a ui-sref=".message({ uid: (mc.user.username || mc.user.uid) })" class="message-nav-btn circle"><span class="glyphicon glyphicon-comment"></span><span class="label label-danger label-as-badge">200</span></a></li>
     <li class="nav-item"><a href="#" data-template="/notifications?ng-view=false" data-placement="left" data-animation="am-slide-left" bs-aside="aside" data-container="body" data-backdrop="false" class="notification-nav-btn circle"><span class="glyphicon glyphicon-globe"></span><span class="label label-danger label-as-badge">1</span></a></li>
     <li class="nav-item"><a href="#" class="settings-nav-btn circle" onclick="return false;" settings-popover><span class="glyphicon glyphicon-cog"></span></a></li>
</ul>
    </nav>
</div>
EOF;
                    } else
                    {
                        return <<<EOF
                        <div class="navbar-fixed-top">
    <nav class="socialSample-navbar" role="navigation">
        <div class="navbar-header">
            <a target="_self" class="navbar-brand" href="/">socialSample</a>
        </div>
<ul class="nav navbar-nav navbar-right">
     <li class="nav-item"><a target="_self" href="/login" class="default-btn">Log in</a></li>
     <li class="nav-item"><a target="_self" href="/signup" class="default-btn">Sign up</a></li>
</ul>
    </nav>
    </div>
EOF;
                    }
            }
    }