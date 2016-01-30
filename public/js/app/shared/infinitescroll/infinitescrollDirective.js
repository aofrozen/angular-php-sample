
angular
    .module('iceberg.infinitescrollDirective', [])
    .directive('infiniteScroll', ['$window', '$document', function (window, $document) {
        var $win = angular.element(window),
            document = $document[0];

        return function ($scope, $element, $attrs) {
            var element = $element[0];

            function windowHeight() {
                var height = document.documentElement.clientHeight;
                return height;
            }

            function scrollTop() {
                var scroll = window.pageYOffset || document.documentElement.scrollTop;
                return scroll;
            }

            function clientHeight() {
                var height = element.clientHeight;
                return height;
            }

            function onScroll() {
                var needScroll = (windowHeight() + scrollTop() - clientHeight()) > 0;

                if (needScroll) {
                    $scope.$apply($attrs.infiniteScroll);
                }
            }

            function onDestroy() {
                $win.unbind('scroll', onScroll);
                $win.unbind('resize', onScroll);
            }

            $scope.$on('$destroy', onDestroy);

            $win.bind('scroll', onScroll);
            $win.bind('resize', onScroll);
        };
    }]);