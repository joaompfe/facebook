'use strict';

angular.module('fb.post')
    .factory('comment', ['utils', function(utils) {
        var comment = {
            getReplies: getReplies,
            writeReply: writeReply
        };

        const baseUrl = 'server/post/comment/';

        return comment;
        
        function getReplies(commentId, quantity, sinceReplyId) {
            return utils.httpPromisse(
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
            return utils.httpPromisse(
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