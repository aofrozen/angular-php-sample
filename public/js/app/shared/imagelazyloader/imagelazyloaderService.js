
angular.module('iceberg.imagelazyloaderService', [])
    .service('scrollAndResizeListener', ['$window', '$document', '$timeout', function ($window, $document, $timeout) {
        var id = 0,
            listeners = {},
            scrollTimeoutId,
            resizeTimeoutId;

        function invokeListeners() {
            var clientHeight = $document[0].documentElement.clientHeight,
                clientWidth = $document[0].documentElement.clientWidth;
            for (var key in listeners) {
                if (listeners.hasOwnProperty(key)) {
                    listeners[key](clientHeight, clientWidth); // call listener with given arguments
                }
            }
        }

        $window.addEventListener('scroll', function () {
            // cancel previous timeout (simulates stop event)
            $timeout.cancel(scrollTimeoutId);
            // wait for 200ms and then invoke listeners (simulates stop event)
            scrollTimeoutId = $timeout(invokeListeners, 200);
        });
        $window.addEventListener('resize', function () {
            $timeout.cancel(resizeTimeoutId);
            resizeTimeoutId = $timeout(invokeListeners, 200);
        });
        return {
            bindListener: function (listener) {
                var index = ++id;
                listeners[id] = listener;
                return function () {
                    delete listeners[index];
                };
            }
        };
    }]
);