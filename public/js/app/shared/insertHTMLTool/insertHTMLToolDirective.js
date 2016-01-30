
angular.module('iceberg.insertHTMLToolDirective', [])
    .directive('insertHtmlContent', ['$sce', '$rootScope', 'ieextensions', 'ieservice', function ($sce, scope, ieext, ieservice) {
        console.log('Found Insert HTML Content Attritube!');
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind('click', function (e) {
                    ieservice.isAttachmentAvailable(e)
                });
                element.bind('keyup', function (e) {
                    ieservice.isAttachmentAvailable(e)
                });
                element.bind('blur', function (e) {
                    ieservice.hideAttachmentMenu()
                });
            }
        }
    }]).directive('insertHtmlTool', ['$sce', '$rootScope', 'ieextensions', 'ieservice', function ($sce, scope, ieext, ieservice) {
        console.log('Found Insert HTML Tool Attritube!');
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                ieservice.appendInsertHTMLMenu(element);
            }
        }
    }]);