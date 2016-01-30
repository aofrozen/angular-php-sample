
angular.module('iceberg.contenteditableDirective', [])
    .directive('contenteditable', ['$filter', function ($filter) {
        return {
            restrict: "A",
            require: "ngModel",
            link: function (scope, element, attrs, ngModel) {
                if (!ngModel) return;
                function read() {

                    ngModel.$setViewValue(element.html());//element.html()
                }

                ngModel.$render = function () {
                    element.html(ngModel.$viewValue || "");
                };

                element.bind("blur keyup change paste", function (event) {
                    if(event.originalEvent.clipboardData)
                    {
                        event.preventDefault();
                        var text = event.originalEvent.clipboardData;
                        text = text.getData('text/plain');
                        document.execCommand("insertText", false, text);
                        scope.$apply(ngModel.$setViewValue(text));
                    }else{
                        scope.$apply(ngModel.$setViewValue(element.html()));
                    }
                    // insert text manually
                });
                read();
            }
        };
    }]);