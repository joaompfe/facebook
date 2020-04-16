'use strict';

angular.module('fb.home', ['fb.post'])
    .controller('HomeCtrl', ['$scope', 'posts', function($scope, posts) {
        $scope.posts = [];          // Defined in init()   
        $scope.postsLoaded = false;
        $scope.newestPost;          // Defined in init()
        $scope.oldestPost;          // Defined in init()

        init();

        function init() {
            posts.getNewPosts(20, 0)
            .then(function(posts) {
                console.log(posts);

                posts.push(...$scope.posts);
                $scope.posts = posts;

                $scope.postsLoaded = true;
                
                $scope.newestPost = posts[0];
                $scope.oldestPost = posts[posts.length-1];
            }, function(error) {
                console.log(error.reason);
            });
        };
    }]);