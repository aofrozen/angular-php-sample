angular.module('iceberg.initDirective', []).directive('init', [function () {
    return {
        restrict: 'E',
        link: function (scope, element, attr) {
            if (attr.initId) {
                scope.init(attr.initId);
            } else {
                scope.init();
            }

        }
    };
}]);