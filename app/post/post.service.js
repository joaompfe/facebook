'use strict';

angular.module('fb.post')
    .factory('post', ['utils', function(utils) {
        var post = {
            getComments: getComments,
            writeComment: writeComment,
            getLikes: getLikes,
            like: like,
            dislike: dislike
        };

        const baseUrl = 'server/post/';

        return post;
        
        function getComments(postId, quantity, sinceCommentId) {
            return utils.httpPromisse(
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
            return utils.httpPromisse(
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

        function getLikes(postId, quantity) {
            return utils.httpPromisse(
                {
                    url: baseUrl + 'readLikes.php', 
                    method: 'GET',
                    params: {
                        postId: postId,
                        quantity: quantity
                    }
                },
                "likes",
                "Read like action failed in server"
            );
        }

        function like(postId) {
            return utils.httpPromisse(
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
            return utils.httpPromisse(
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
    }]);