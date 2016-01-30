
angular.module('iceberg.idleService', []).factory('idleService', [function(){

        if (!document.addEventListener) {
            if (document.attachEvent) {
                document.addEventListener = function(event, callback, useCapture) {
                    return document.attachEvent("on" + event, callback, useCapture);
                };
            } else {
                document.addEventListener = function() {
                    return {};
                };
            }
        }

        if (!document.removeEventListener) {
            if (document.detachEvent) {
                document.removeEventListener = function(event, callback) {
                    return document.detachEvent("on" + event, callback);
                };
            } else {
                document.removeEventListener = function() {
                    return {};
                };
            }
        }

        "use strict";

        var idleService = {};


            idleService.isAway = false;

            idleService.awayTimeout = 3000;

            idleService.awayTimestamp = 0;

            idleService.awayTimer = null;

            idleService.onAway = null;

            idleService.onAwayBack = null;

            idleService.onVisible = null;

            idleService.onHidden = null;

            idleService.idle = function(options) {
                var activeMethod, activity;
                if (options) {
                    idleService.awayTimeout = parseInt(options.awayTimeout, 10);
                    idleService.onAway = options.onAway;
                    idleService.onAwayBack = options.onAwayBack;
                    idleService.onVisible = options.onVisible;
                    idleService.onHidden = options.onHidden;
                }
                activity = idleService;
                activeMethod = function() {
                    return activity.onActive();
                };
                window.onclick = activeMethod;
                window.onmousemove = activeMethod;
                window.onmouseenter = activeMethod;
                window.onkeydown = activeMethod;
                window.onscroll = activeMethod;
                window.onmousewheel = activeMethod;
            };

            idleService.onActive = function() {
                idleService.awayTimestamp = new Date().getTime() + idleService.awayTimeout;
                if (idleService.isAway) {
                    if (idleService.onAwayBack) {
                        idleService.onAwayBack();
                    }
                    idleService.start();
                }
                idleService.isAway = false;
                return true;
            };

            idleService.start = function() {
                var activity;
                if (!idleService.listener) {
                    idleService.listener = (function() {
                        return activity.handleVisibilityChange();
                    });
                    document.addEventListener("visibilitychange", idleService.listener, false);
                    document.addEventListener("webkitvisibilitychange", idleService.listener, false);
                    document.addEventListener("msvisibilitychange", idleService.listener, false);
                }
                idleService.awayTimestamp = new Date().getTime() + idleService.awayTimeout;
                if (idleService.awayTimer !== null) {
                    clearTimeout(idleService.awayTimer);
                }
                activity = idleService;
                idleService.awayTimer = setTimeout((function() {
                    return activity.checkAway();
                }), idleService.awayTimeout + 100);
                return idleService;
            };

            idleService.stop = function() {
                if (idleService.awayTimer !== null) {
                    clearTimeout(idleService.awayTimer);
                }
                if (idleService.listener !== null) {
                    document.removeEventListener("visibilitychange", idleService.listener);
                    document.removeEventListener("webkitvisibilitychange", idleService.listener);
                    document.removeEventListener("msvisibilitychange", idleService.listener);
                    idleService.listener = null;
                }
                return idleService;
            };

            idleService.setAwayTimeout = function(ms) {
                idleService.awayTimeout = parseInt(ms, 10);
                return idleService;
            };

            idleService.checkAway = function() {
                var activity, t;
                t = new Date().getTime();
                if (t < idleService.awayTimestamp) {
                    idleService.isAway = false;
                    activity = idleService;
                    idleService.awayTimer = setTimeout((function() {
                        return activity.checkAway();
                    }), idleService.awayTimestamp - t + 100);
                    return;
                }
                if (idleService.awayTimer !== null) {
                    clearTimeout(idleService.awayTimer);
                }
                idleService.isAway = true;
                if (idleService.onAway) {
                    return idleService.onAway();
                }
            };

            idleService.handleVisibilityChange = function() {
                if (document.hidden || document.msHidden || document.webkitHidden) {
                    if (idleService.onHidden) {
                        return idleService.onHidden();
                    }
                } else {
                    if (idleService.onVisible) {
                        return idleService.onVisible();
                    }
                }
            };

            return idleService;
}]);