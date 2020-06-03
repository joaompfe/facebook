'use strict';

angular.module('fb.post')
    .factory('post', ['$location', 'server', function($location, server) {
        var post = {
            getComments: getComments,
            writeComment: writeComment,
            getLikes: getLikes,
            like: like,
            dislike: dislike,
            redirectToProfile: redirectToProfile
        };

        const baseUrl = 'server/post/';

        return post;
        
        function getComments(postId, quantity, sinceCommentId) {
            return server.httpPromisse(
                {
                    url: baseUrl + 'readComments.php', 
                    method: 'GET',
                    params: {
                        postId: postId,
                        quantity: quantity,
                        commentId: sinceCommentId
                    }
                },
                "comments",
                "Server returned no comment"
            );
        }

        function writeComment(postId, content) {
            return server.httpPromisse(
                {
                    url: baseUrl + 'writeComment.php', 
                    method: 'POST',
                    data: {
                        postId: postId,
                        content: content
                    }
                },
                "comment",
                "Write comment failed in server"
            );
        }

        function getLikes(postId, quantity, sinceId) {
            return server.httpPromisse(
                {
                    url: baseUrl + 'readLikes.php', 
                    method: 'GET',
                    params: {
                        postId: postId,
                        quantity: quantity,
                        sinceId: sinceId
                    }
                },
                "likes",
                "Read like action failed in server"
            );
        }

        function like(postId) {
            return server.httpPromisse(
                {
                    url: baseUrl + 'like.php', 
                    method: 'GET',
                    params: {
                        postId: postId
                    }
                },
                "like",
                "Like action failed in server"
            );
        }

        function dislike(postId) {
            return server.httpPromisse(
                {
                    url: baseUrl + 'dislike.php', 
                    method: 'GET',
                    params: {
                        postId: postId
                    }
                },
                null,
                "Dislike action failed in server"
            );
        }

        function redirectToProfile(id) {
            $location.url("/profile/" + id);
        }
    }]);