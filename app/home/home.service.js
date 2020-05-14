'use strict';

angular.module('fb.home')
    .factory('posts', ['server', function(server) {
        var posts = {
            getNewPosts: getNewPosts
        };

        return posts;

        function getNewPosts(quantity, sincePostId) {
            return server.httpPromisse(
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
    }]);