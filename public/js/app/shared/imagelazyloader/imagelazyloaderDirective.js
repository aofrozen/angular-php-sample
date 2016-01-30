
angular.module('iceberg.imagelazyloaderDirective', [])
    .directive(
    'imageLazySrc', ['$document', 'scrollAndResizeListener', function ($document, scrollAndResizeListener) {
        return {
            restrict: 'A',
            link: function ($scope, $element, $attributes) {
                var listenerRemover;

                function isInView(clientHeight, clientWidth) {
                    // get element position
                    var imageRect = $element[0].getBoundingClientRect();
                    if (
                        (imageRect.top >= 0 && imageRect.bottom <= clientHeight) && (imageRect.left >= 0 && imageRect.right <= clientWidth)
                    ) {
                        $element[0].src = $attributes.imageLazySrc; // set src attribute on element (it will load image)

                        // unbind event listeners when image src has been set
                        listenerRemover();
                    }
                }

                // bind listener
                listenerRemover = scrollAndResizeListener.bindListener(isInView);
                // unbind event listeners if element was destroyed
                // it happens when you change view, etc
                $element.on('$destroy', function () {
                    listenerRemover();
                });
                // explicitly call scroll listener (because, some images are in viewport already and we haven't scrolled yet)
                isInView(
                    $document[0].documentElement.clientHeight,
                    $document[0].documentElement.clientWidth
                );
            }
        };
    }]
).directive('wallLazySrc', function () {
        return {
            restrict: 'A',
            link: function ($scope, $element, $attributes) {
                if ($attributes.wallLazySrc) {
                    $element.css('background', 'linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.0)), url(' + $attributes.wallLazySrc + ')');
                } else {
                    $element.css('background-color', '#3FA0B4');
                }
            }
        };
    });