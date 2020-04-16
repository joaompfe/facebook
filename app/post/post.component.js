(function() {
    'use strict'

    angular.module('fb.post')
        .component('postComp', {
            templateUrl: 'app/post/post.html',
            bindings: {
                post: '<'
            },
            controllerAs: 'vm',
            controller: ['post', postController]
        })
        .controller('CommentCtrl', ['$scope', 'comment', commentController]);

    function postController(post) {
        var vm = this;

        vm.wereCommentsRequested = false;   // Set to true when viewMoreComments() is called the first time
        vm.oldestComment;                   // Updated when viewMoreComments() is called.

        vm.isEmpty = isEmpty;
        vm.showMoreComments = getMoreComments;

        //not used
        function isEmpty(array) {
            return (Array.isArray(array) && array.length == 0);
        }

        function getMoreComments() {
            if (vm.wereCommentsRequested) {
                post.getComments(vm.post.id, 2, vm.oldestComment.id)
                    .then(function(comments) {
                        vm.post.comments.push(...comments);
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
    }

    function commentController($scope, comment) {
        
        $scope.wereCommentsRequested = false;   // Set to true when viewMoreComments() is called the first time
        $scope.oldestComment;                   // Updated when viewMoreComments() is called.

        $scope.showMoreComments = getMoreComments;

        function getMoreComments() {
            if ($scope.wereCommentsRequested) {
                comment.getComments($scope.comment.id, 2, $scope.oldestComment.id)
                    .then(function(comments) {
                        $scope.comment.comments.push(...comments);
                        $scope.oldestComment = $scope.comment.comments[$scope.comment.comments.length - 1];
                    }, function(error) {
                        console.log(error.reason);
                    });
            }
            else {
                comment.getComments($scope.comment.id, 2)
                    .then(function(comments) {
                        $scope.comment.comments = comments;
                        $scope.oldestComment = $scope.comment.comments[$scope.comment.comments.length - 1];
                        $scope.wereCommentsRequested = true;
                    }, function(error) {
                        console.log(error.reason);
                    });
            }
        }
    }
})();