(function() {
    'use strict'

    angular.module('fb.post')
        .component('postComp', {
            templateUrl: 'app/post/post.html',
            bindings: {
                post: '<'
            },
            controllerAs: 'vm',
            controller: ['post', 'client', postController]
        });

    function postController(post, client) {
        var vm = this;

        vm.wereCommentsRequested = false;   // Set to true when viewMoreComments() is called the first time
        vm.oldestComment;                   // Updated when viewMoreComments() is called.
        vm.clientLike = null;               // Set on $onInit if client likes the post

        vm.showMoreComments = getMoreComments;
        vm.writeComment = writeComment;
        vm.appendComments = appendComments;
        vm.focusCommentInput = focusCommentInput;
        vm.toggleLike = toggleLike;
        vm.appendLikes = appendLikes;
        vm.removeLike = removeLike;
        vm.$onInit = onInit;
        vm.redirectToProfile = redirectToProfile;
        
        /**
         * Iterate through likes array and test if user client likes the post.
         * Save the like in vm.clientLike for possible toggleLike() operations.
         */
        function onInit() {
            for (var like of vm.post.likes) {
                if (like.author.id == client.id) {
                    vm.clientLike = like;
                    break;
                }
            }
        }

        function getMoreComments() {
            if (vm.wereCommentsRequested) {
                post.getComments(vm.post.id, 2, vm.oldestComment.id)
                    .then(function(comments) {
                        vm.appendComments(...comments);
                        vm.oldestComment = vm.post.comments[vm.post.comments.length - 1];
                    }, function(error) {
                        console.log(error.reason);
                    });
            }
            else {
                post.getComments(vm.post.id, 2)
                    .then(function(comments) {
                        vm.post.comments = comments;
                        vm.oldestComment = vm.post.comments[vm.post.comments.length - 1];
                        vm.wereCommentsRequested = true;
                    }, function(error) {
                        console.log(error.reason);
                    });
            }   
        }

        function writeComment() {
            if (vm.commentContent != null && vm.commentContent != "") {
                post.writeComment(vm.post.id, vm.commentContent)
                .then(function sucess(writedComment) {
                    vm.commentContent = "";
                    vm.post.amountOfCommentsInDatabase++;
                    vm.appendComments(writedComment);
                }, function (error) {
                    console.log(error.reason);
                    // TODO show error message to user ?
                });
            }
        }

        function appendComments() {
            vm.post.comments.push(...arguments);
        }

        function focusCommentInput() {
            vm.commentInput.focus();
        }

        function toggleLike() {
            if (vm.clientLike) {
                vm.tempClientLike = vm.clientLike;  // Save clientLike in temporary var for recovery in case of server rejection
                vm.clientLike = null;   // Assuming that server accepts the request. This allows for a more reactive interface
                post.dislike(vm.post.id)
                    .then(function() {
                        vm.removeLike(vm.tempClientLike);
                        vm.clientLike = null;
                        delete vm.tempClientLike;
                    }, function(error) {
                        vm.clientLiket = vm.tempClientLike;
                        delete vm.tempClientLike;
                        console.log("reason", error.reason);
                    });
            }
            else {
                vm.clientLike = true;   // Assuming that server accepts the request. This allows for a more reactive interface
                post.like(vm.post.id)
                    .then(function(like) {
                        vm.clientLike = like;
                        vm.appendLikes(like);
                    }, function(error) {
                        vm.clientLike = null;
                        console.log("reason", error.reason);
                    });
            }
        }

        function appendLikes() {
            vm.post.likes.push(...arguments);
        }

        function removeLike(like) {
            const index = vm.post.likes.indexOf(like);
            if (index > -1) {
                vm.post.likes.splice(index, 1);
            }
        }

        function redirectToProfile(id) {
            post.redirectToProfile(id);
        }
    }

})();