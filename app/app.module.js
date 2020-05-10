(function(angular) {
  'use strict';
angular.module('fb', ['fb.home', 'fb.login', 'fb.profile', 'ngRoute'])
    .config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
        $routeProvider
            .when('/', {
                templateUrl: 'app/home/homepage.html',
                controller: 'HomeCtrl'
            })
            .when('/profile/:id',{
               templateUrl: 'app/profile/profile.html',
               controller: 'ProfileCtrl'
            })
            .when('/login', {
                templateUrl: 'app/login/login.html',
                controller: 'LoginCtrl'
            })
            .otherwise('/');

        $locationProvider.html5Mode(true);
    }])
    .run(['$location', 'client', '$rootScope', function($location, client, $rootScope) {
        $rootScope.client = client;
        // if no user client is sessioned redirect to login page, otherwise go
        // to home page.
        client.getClient()
            .then(function(client) {
            }, function(error) {
                $location.url('/login');
            });
    }]);
})(window.angular);


