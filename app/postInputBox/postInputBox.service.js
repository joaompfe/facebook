'use strict';

angular.module('fb')
    .factory('postInputBox', ['server', function(server) {
        var postInputBox = {
            writePost: writePost
        };

        return postInputBox;

        function writePost(content) {
            return server.httpPromisse(
                {
                    url: 'server/writePost.php', 
                    method: 'POST',
                    data: {
                        content: content
                    }
                },
                null,
                "Write post failed in server"
            );
        }
    }]);