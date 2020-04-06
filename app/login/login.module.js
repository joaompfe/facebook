'use strict';

angular.module('fb.login', [])
    .controller('LoginCtrl', ['$scope', 'client', function($scope, client) {
        $scope.submit = function() { 
            client.login($scope.email, $scope.password);
        };
    }]);

