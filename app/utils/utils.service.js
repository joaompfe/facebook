(function() {
    'use strict';

    angular.module('fb.utils')
        .factory('utils', ['$http', '$q', utils]);

    function utils($http, $q) {
        var utils = {
            httpPromisse: httpPromisse
        };

        return utils;

        /**
         * Call http service with passed parameters. On success, pass a response.data 
         * property specified by dataPropertyToPassToResolve param to resolve
         * function. On intentional fail by server pass an object to reject function
         * with a reason message defined by serverRejectionMsg param. On $http fail 
         * log the response and pass an object to reject function with the reason 
         * "HTTP failed"
         * 
         * @param {*} httpParams 
         * @param {String} dataPropertyToPassToResolve 
         * @param {String} serverRejectionMsg 
         */
        function httpPromisse(httpParams, dataPropertyToPassToResolve, serverRejectionMsg) {

            return $q(function(resolve, reject) {
                $http(httpParams)
                .then(function(response) {
                    if (response.data.success) {
                        if (dataPropertyToPassToResolve) {
                            resolve(response.data[dataPropertyToPassToResolve]);
                        }
                        else {
                            resolve();
                        }
                    }
                    else {
                        reject({reason: serverRejectionMsg, httpResponse: response});
                    }
                }, function(response) {
                    console.log(response);
                    reject({reason: "HTTP failed", httpResponse: response});
                });
            });
        }
    }
})();