
angular.module('iceberg.insertHTMLToolExtensionService', [])
    .service('ieextensions', [function () {
        this.extensions = [{'extName': 'video'}, {'extName': 'image'}];
        videoExtension = {
            'insertVideo': function (callback) {

            }
        }
        insertVideo = function (callback) {
            //alert('video input');
            //callback('<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="//player.vimeo.com/video/59179537" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div><p><a href="https://vimeo.com/59179537">The Gift</a> from <a href="https://vimeo.com/miniestudio">miniestudio</a> on <a href="https://vimeo.com">Vimeo</a>.</p>');
        }
        this.test = function () {
            console.warn('clicked!')
        }
    }]);