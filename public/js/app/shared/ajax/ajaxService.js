
angular.module('iceberg.ajaxService', [])
    .service('ajaxService', ['$log', '$http', function ($log, $http) {
        this._ajax = function (method, url, data, callback) {
            $log.info('Called Ajax. Method: ' + method + url);
            $http({
                'method': method,
                'url': url,
                'data': data,
                'cache': false
            }).success(function (data, status, headers, config) {
                $log.info(data);
                if(typeof data.errorLogin !== 'undefined')
                {
                    console.error('required to log in!');
                    //window.location = '/login';
                }
                callback({'data': data, 'status': status, 'headers': headers, 'config': config});
            }).error(function (data, status, headers, config) {
                $log.error(url + ' response failed (GET)');
                callback({'data': data, 'status': status, 'headers': headers, 'config': config});
            });
        };
    }]);