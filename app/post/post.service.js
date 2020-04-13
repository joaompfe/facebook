'use strict';

angular.module('fb.post')
    .factory('posts', ['$http', '$q', function($http, $q) {
        var posts = {
            getNewPosts: getNewPosts
        };

        return posts;

        function getNewPosts(quantity, sincePostId) {
            return $q(function(resolve, reject) {
                $http({
                    url: 'server/posts.php', 
                    method: 'GET',
                    params: {
                        quantity: quantity,
                        type: 'new',
                        postId: sincePostId
                    }
                })
                .then(function(response) {
                    console.log("getNewPosts service");
                    console.log(response);
                    if (!angular.equals(response.data, {})) {
                        resolve(response.data);
                    }
                    else {
                        reject({reason: "Server returned no posts", httpResponse: response});
                    }
                }, function(response) {
                    console.log(response);
                    reject({reason: "HTTP failed", httpResponse: response});
                });
            });
        }
    }]);