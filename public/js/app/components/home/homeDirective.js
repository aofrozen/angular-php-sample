
angular.module('socialSample.homeDirective', [])
    .directive('homeWallRotationToggle', ['homeService', function(homeService){
        return{
            restrict: 'A',
            link: function(scope, element, attr){
                element.bind('click', function(e){
                    homeService._wallRotationToggle(function(response)
                    {
                        scope.$apply(function(){
                           scope.mc.home = response;
                        });
                        console.info(response);
                    });
                });
            }
        }
}])
    .directive('homeWallPositionToggle', ['homeService', function(homeService){
        return{
            restrict: 'A',
            link: function(scope, element, attr){
                element.bind('click', function(e){
                    homeService._wallPositionToggle(function(response){
                        scope.$apply(function(){
                            scope.mc.home = response;
                        });
                        console.info(response);
                    });
                });
            }
        }
    }]);