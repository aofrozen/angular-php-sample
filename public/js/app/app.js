/* Other Components */
require('./../../../bower_components/jquery/jquery.js');
require('angular');
//require('./../../../bower_components/famous/dist/famous-global');
//require('./../../../bower_components/famous-angular/dist/famous-angular');
//require('./../../../bower_components/ngCordova/dist/ng-cordova');
//require('./../../../bower_components/angular-medium-editor/dist/angular-medium-editor')
require('./../../../bower_components/angular-route/angular-route');
require('./../../../bower_components/angular-scroll/angular-scroll');
require('./../../../bower_components/angular-animate/angular-animate');
require('./../../../bower_components/angular-sanitize/angular-sanitize');
require('./../../../bower_components/angular-strap/dist/angular-strap');
require('./../../../bower_components/angular-strap/dist/angular-strap.tpl');
require('./../../../bower_components/ngprogress/build/ngProgress');
require('./../../../bower_components/angular-file-upload/angular-file-upload');
require('./../../../bower_components/angular-progress-arc/angular-progress-arc');
require('./../../../bower_components/firebase/firebase');
//require('./../../../public/firebase');
require('./../../../bower_components/angularfire/dist/angularfire');
require('./../../../bower_components/angular-ui-router/release/angular-ui-router');
require('./../../../bower_components/ui-router-extras/release/ct-ui-router-extras');
require('./../../../bower_components/ng-videosharing-embed/build/ng-videosharing-embed.min');
require('./../../../bower_components/angular-emoji-filter-hd/lib/emoji');
require('./../../../bower_components/perfect-scrollbar/src/perfect-scrollbar');
require('./../../../bower_components/angular-perfect-scrollbar/src/angular-perfect-scrollbar');
require('./../../../bower_components/ng-idle/angular-idle');
require('./../../../bower_components/ng-tags-input/ng-tags-input');
//require('./shared/editor/editor');
//require('JSXTransformer');
//require('react');
//require('ngReact');


/* socialSample Components */
require('./components/socialSample/socialSampleController');
require('./components/socialSample/socialSampleDirective');

/* Auth Components */
require('./components/auth/login/loginController');
require('./components/auth/signup/signupController');
require('./components/auth/recovery/loginRecoveryController');


/* User Components */
require('./components/user/userService');


/* Home Components */
require('./components/home/homeController');
require('./components/home/homeService');
require('./components/home/homeDirective');

/* Settings Components */
require('./components/settings/settingsController');
require('./components/settings/settingsDirective');
require('./components/settings/settingsService');


/* Timeline Components */
require('./components/feed/feedService');
require('./components/feed/feedController');
require('./components/feed/feedDirective');
require('./components/feed/feedFilter');

/* Notification Components */
require('./components/notification/notificationController');
require('./components/notification/notificationService');
require('./components/notification/notificationDirective');


/* Profile Components */
require('./components/profile/photo/profilePhotoController');
require('./components/profile/photo/profilePhotoService');
require('./components/profile/photo/profilePhotoDirective');
require('./components/profile/friend/profileFriendController');
require('./components/profile/friend/profileFriendService');
require('./components/profile/about/profileAboutService');
require('./components/profile/about/profileAboutController');
require('./components/profile/profileController');
require('./components/profile/profileDirective');
require('./components/profile/profileService');


/* Message Components */
require('./components/message/messageController');
require('./components/message/messageService');
require('./components/message/messageDirective');

/* Journal Components */
require('./components/journal/journalController');


/* Shared Components */
require('./shared/ajax/ajaxService');
require('./shared/imagelazyloader/imagelazyloaderDirective');
require('./shared/imagelazyloader/imagelazyloaderService');
require('./shared/contenteditable/contenteditableDirective');
require('./shared/follow/followDirective');
require('./shared/infinitescroll/infinitescrollDirective');
require('./shared/headroom/headroom');
require('./shared/headroom/headroomDirective');
require('./shared/init/initDirective');
require('./shared/flowgrid/ngflowgrid');
require('./shared/idle/idle');
require('./shared/filters/filters');

//require('./shared/insertHTMLTool/insertHTMLToolDirective');
//require('./shared/insertHTMLTool/insertHTMLToolExtensionService');
//require('./shared/insertHTMLTool/insertHTMLToolService');


/* Router */
require('./router');



/* Modules */
var modules = [
    /* Other Modules */
    'dbaq.emoji',
    'videosharing-embed',
    'firebase',
    'ui.router',
    'ngRoute',
    'ngFlowGrid',
    'angular-progress-arc',
    'headroom',
    'duScroll',
    'mgcrea.ngStrap',
    'ngSanitize',
    'ngAnimate',
    'ngProgress.provider',
    'angularFileUpload',
    'perfect_scrollbar',
    'ngIdle',
    'ngTagsInput',

    /* Settings Modules */
    'socialSample.settings',
    'socialSample.settingsService',

    /* Auth Modules */
    'socialSample.authLogin',
    'socialSample.authRecovery',
    'socialSample.authSignup',

    /* Profile Modules */
    'socialSample.profileService',
    'socialSample.profile',

    'socialSample.profilePhoto',
    'socialSample.profileDirective',
    'socialSample.profilePhotoService',
    'socialSample.profilePhotoDirective',
    'socialSample.profileFriend',
    'socialSample.profileFriendService',
    'socialSample.profileAbout',
    'socialSample.profileAboutService',

    /* Notification Modules */
    'socialSample.notification',
    'socialSample.notificationService',
    'socialSample.userService',

    /* Home Modules */
    'socialSample.home',
    'socialSample.homeService',
    'socialSample.homeDirective',
    'socialSample.journal',

    /* socialSample */
    'socialSample.socialSample',
    'socialSample.socialSampleDirective',

    /* Feed */
    'socialSample.feed',
    'socialSample.feedService',
    'socialSample.feedDirective',
    'socialSample.feedFilter',

    /* Message Modules */
    'socialSample.message',
    'socialSample.messageDirective',
    'socialSample.messageService',

    /* Shared Modules */
    'iceberg.filters',
    'iceberg.idleService',
    'iceberg.ajaxService',
    'iceberg.imagelazyloaderService',
    'iceberg.imagelazyloaderDirective',
    'iceberg.initDirective',
    'iceberg.infinitescrollDirective',
    'iceberg.contenteditableDirective', //ng-model for contenteditable
    /* Router Modules */
    'socialSample.router'
];

angular.module('socialSampleApp', modules);