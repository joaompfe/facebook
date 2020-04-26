(function() {
    'use strict'

    angular.module('fb.post')
        .component('commentComp',{
                templateUrl: 'app/post/comment/comment.html',
                bindings: {
                    comment: '<'
                },
                controllerAs: 'vm',
                controller: ['comment', commentController]
            
        });

    function commentController(comment) {
        var vm = this;

        vm.wereRepliesRequested = false;   // Set to true when showMoreReplies() is called the first time
        vm.oldestReply;                   // Updated when viewMoreComments() is called.
        vm.wasReplyClicked = false;         // Set to true when user clicks 'Reply'. Then reply input box appears to user write the comment

        vm.showMoreReplies = getMoreReplies;
        vm.writeReply = writeReply;
        vm.appendReplies = appendReplies;
        vm.focusReplyInput = showReplyInput;

        function getMoreReplies() {
            if (vm.wereRepliesRequested) {
                comment.getReplies(vm.comment.id, 2, vm.oldestReply.id)
                    .then(function(replies) {
                        vm.appendReplies(...replies)
                        vm.oldestReply = vm.comment.replies[vm.comment.replies.length - 1];
                    }, function(error) {
                        console.log(error.reason);
                    });
            }
            else {
                comment.getReplies(vm.comment.id, 2)
                    .then(function(replies) {
                        vm.comment.replies = replies;
                        vm.oldestReply = vm.comment.replies[vm.comment.replies.length - 1];
                        vm.wereRepliesRequested = true;
                    }, function(error) {
                        console.log(error.reason);
                    });
            }
        }

        function writeReply() {
            if (vm.replyContent != null && vm.replyContent != "") {
                comment.writeReply(vm.comment.id, vm.replyContent)
                .then(function (writedReply) {
                    vm.replyContent = "";
                    vm.comment.amountOfRepliesInDatabase++;
                    vm.appendReplies(writedReply);
                }, function (error) {
                    console.log(error.reason);
                    // TODO show error message to user 
                });
            }
        }

        function appendReplies() {
            vm.comment.replies.push(...arguments);
        }

        /**
         * On the first click to 'Reply' there's no reply input box, so showReplyInput 
         * is called. After that, every time user clicks 'Reply', because input box already
         * exists, just focus input box.
         */
        function showReplyInput() {
            vm.wasReplyClicked = true;
            vm.focusReplyInput = function() { vm.replyInput.focus(); };
        }
    }

})();