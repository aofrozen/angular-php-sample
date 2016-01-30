
angular.module('iceberg.filters', []).filter('reverse', function() {
    return function(items) {
        if(typeof items !== 'undefined')
        return items.slice().reverse();
    };
});