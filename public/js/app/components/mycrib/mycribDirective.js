/**
 * Created by Justin on 5/25/15.
 */
angular.module('socialSample.socialSampleDirective', [])
    .directive('settingsPopover', ['$popover', function($popover){
    return {
        restrict: 'A',
        link: function(scope, element, attr){
            var myPopover = $popover(element, {
                contentTemplate: '/view/settings-menu-popover',
                html: true,
                trigger: 'manual',
                autoClose: true,
                scope: scope,
                placement: 'bottom',
                animation: ''
            });
            element.bind('click', function(){
                console.info('popover!');
                myPopover.show();
            });
        }
    }
}]);