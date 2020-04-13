'use strict';
angular.module('fb.home', ['fb.post'])
    .controller('HomeCtrl', ['$scope', 'posts', function($scope, posts) {
        console.log("Home module controller");

        $scope.posts = [];
        $scope.postsLoaded = false;

        posts.getNewPosts(20, 0)
            .then(function(posts) {
                console.log("Home module controller2");
                console.log(posts);

                posts.push(...$scope.posts);
                $scope.posts = posts;
                $scope.postsLoaded = true;
                $scope.newestPostId = posts[0].id;
                $scope.oldestPostId = posts[posts.length-1].id;
            }, function(error) {
                console.log(error.reason);
            });
    }]);