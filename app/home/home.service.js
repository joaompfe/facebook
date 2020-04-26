'use strict';

angular.module('fb.home')
    .factory('posts', ['$http', '$q', 'utils', function($http, $q, utils) {
        var posts = {
            getNewPosts: getNewPosts
        };

        return posts;

        function getNewPosts(quantity, sincePostId) {
            return utils.httpPromisse(
                {
                    url: 'server/readPosts.php', 
                    method: 'GET',
                    params: {
                        quantity: quantity,
                        type: 'new',
                        postId: sincePostId
                    }
                },
                "posts",
                "Server returned no post"
            );
        }

        /*
        function getNewPosts(quantity, sincePostId) {
            return $q(function(resolve, reject) {
                $http({
                    url: 'server/readPosts.php', 
                    method: 'GET',
                    params: {
                        quantity: quantity,
                        type: 'new',
                        postId: sincePostId
                    }
                })
                .then(function(response) {
                    if (response.data.success) {
                        resolve(response.data.posts);
                    }
                    else {
                        reject({reason: "Server returned no post", httpResponse: response});
                    }
                }, function(response) {
                    console.log(response);
                    reject({reason: "HTTP failed", httpResponse: response});
                });
            });
        }*/
    }]);