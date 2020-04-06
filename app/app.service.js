'use strict';
angular.module('fb')
    // return user client asking server for session client. If no session client
    // is available in server, {} is returned
    .factory('client', ['$http', '$q', '$location', function($http, $q, $location) {
        var client = {};
        
        client.isLogged = false;
        client.getClient = getClient;
        client.logout = logout;
        client.login = login;
        
        function getClient() {
            return $q(function(resolve, reject) {
                $http.get('server/client.php')
                .then(function(response) {
                    if (!angular.equals(response.data, {})) {
                        console.log(response.data);
                        for (var k in response.data) 
                            client[k]=response.data[k];
                        console.log(client);
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
                    console.log(response.data);
                    for (var k in response.data.client) 
                        client[k]=response.data.client[k];
                    console.log(client);
                    client.isLogged = true;
                    $location.url('/');
                }
                else {
                    console.log(response.data);
                    console.log("Invalid email or password");
                }
            }, 
            function(response) { 
                console.log(response.statusText);
            });
        }
        
        function logout() {
            $http.get('server/logout.php')
                .then(function(response) {
                }, function(response) {
                });
            client.isLogged = false;
            $location.url('/login');
        }
        
        return client;
    }]);


