'use strict';

angular.module('fb.post')
    .factory('comment', ['server', function(server) {
        var comment = {
            getReplies: getReplies,
            writeReply: writeReply
        };

        const baseUrl = 'server/post/comment/';

        return comment;
        
        function getReplies(commentId, quantity, sinceReplyId) {
            return server.httpPromisse(
                {
                    url: baseUrl + 'readReplies.php', 
                    method: 'GET',
                    params: {
                        commentId: commentId,
                        quantity: quantity,
                        commentReplyId: sinceReplyId
                    }
                },
                "replies",
                "Server returned no comment reply"
            );
        }

        function writeReply(commentId, content) {
            return server.httpPromisse(
                {
                    url: baseUrl + 'writeReply.php', 
                    method: 'POST',
                    data: {
                        commentId: commentId,
                        content: content
                    }
                },
                "reply",
                "Write comment reply failed in server"
            );
        }
}]);