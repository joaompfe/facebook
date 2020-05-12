'use strict';
angular.module('fb')
    // return user client asking server for session client. If no session client
    // is available in server, {} is returned.
    .factory('client', ['$http', '$q', '$location', function($http, $q, $location) {
        var client = {
            isLogged: false,
            getClient: getClient,
            logout: logout,
            login: login
        };

        return client;
        
        function getClient() {
            // Request server for sessioned client.
            return $q(function(resolve, reject) {
                $http.get('server/client.php')
                .then(function(response) {
                    if (!angular.equals(response.data, {})) {
                        for (var k in response.data) 
                            client[k]=response.data[k];
                        client.isLogged = true;
                        resolve(response.data);
                    }
                    else {
                        reject({reason: "Server returned no client", httpResponse: response});
                    }
                }, function(response) {
                    reject({reason: "HTTP failed", httpResponse: response});
                });
            });
        }
        

        function login(email, password) {
            $http({
                url: 'server/login.php',
                method: 'POST',
                data: { 
                    email: email, 
                    password: password
                }
            })
            .then(function(response) {
                if (response.data.loggedSucessfully) {
                    for (var k in response.data.client) 
                        client[k]=response.data.client[k];
                    client.isLogged = true;
                    $location.url('/');
                }
                else {
                    console.log("Invalid email or password", response.data);
                }
            }, 
            function(response) { 
                console.log("HTTP failed", response.statusText);
            });
        }
        
        function logout() {
            // Logout, drop client session in server.
            $http.get('server/logout.php')
                .then(function(response) {
                }, function(response) {
                });
            client.isLogged = false;
            $location.url('/login');
        }
    }])
    .factory('signUp', ['$http', '$q', function($http, $q) {
        return function(firstName, lastName, email, password, birthday, gender) {
            return $q(function(resolve, reject) {
                $http({
                    url: 'server/signup.php',
                    method: 'POST',
                    data: { 
                        firstName: firstName,
                        lastName: lastName,
                        email: email, 
                        password: password,
                        birthday: birthday,
                        gender: gender
                    }
                })
                .then(function(response) {
                    if (response.data.signUpSucessfully) {
                        resolve(response);
                    }
                    else {
                        console.log("SignUp failed", response.data);
                        reject({reason: "SignUp failed", httpResponse: response});
                    }
                }, 
                function(response) { 
                    reject({reason: "HTTP failed", httpResponse: response});
                });
            });
        }
            
    }]);


