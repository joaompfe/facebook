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
                    if (!angular.equals(response.data, {})) {
                        resolve(response.data);
                    }
                    else {
                        reject({reason: "Server returned no post", httpResponse: response});
                    }
                }, function(response) {
                    console.log(response);
                    reject({reason: "HTTP failed", httpResponse: response});
                });
            });
        }
    }])
    .factory('post', ['$http', '$q', function($http, $q) {
        var post = {
            getComments: getComments
        };

        return post;

        function getComments(postId, quantity, sinceCommentId) {
            return $q(function(resolve, reject) {
                $http({
                    url: 'server/postcomments.php', 
                    method: 'GET',
                    params: {
                        postId: postId,
                        quantity: quantity,
                        commentId: sinceCommentId
                    }
                })
                .then(function(response) {
                    if (!angular.equals(response.data, {})) {
                        resolve(response.data);
                    }
                    else {
                        reject({reason: "Server returned no comment", httpResponse: response});
                    }
                }, function(response) {
                    console.log(response);
                    reject({reason: "HTTP failed", httpResponse: response});
                });
            });
        }
    }])
    .factory('comment', ['$http', '$q', function($http, $q) {
        var comment = {
            getComments: getComments
        };

        return comment;

        function getComments(commentId, quantity, sinceSubCommentId) {
            console.log(sinceSubCommentId);
            return $q(function(resolve, reject) {
                $http({
                    url: 'server/commentcomments.php', 
                    method: 'GET',
                    params: {
                        commentId: commentId,
                        quantity: quantity,
                        commentCommentId: sinceSubCommentId
                    }
                })
                .then(function(response) {
                    console.log("getCComments service");
                    console.log(response);
                    if (!angular.equals(response.data, {})) {
                        resolve(response.data);
                    }
                    else {
                        reject({reason: "Server returned no sub comment", httpResponse: response});
                    }
                }, function(response) {
                    console.log(response);
                    reject({reason: "HTTP failed", httpResponse: response});
                });
            });
        }
    }]);