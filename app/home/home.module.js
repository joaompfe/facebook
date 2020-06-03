'use strict';

angular.module('fb.home', ['fb.post'])
    .controller('HomeCtrl', ['$scope', '$anchorScroll', '$timeout', '$location', '$interval', 'posts',
    function($scope, $anchorScroll, $timeout, $location, $interval, posts) {
        $scope.posts = [];  
        $scope.postsLoaded = false;
        $scope.newestPost = { id: 0 };  // Serve como referência para pedir os posts mais recentes
        $scope.oldestPost = { id: 0 };  // Servirá como referência quando o utilizador faz scroll para baixo pedir os mais antigos

        init();

        function init() {
            getPostsInit();

            $timeout(function() {
                //$location.hash('top');
                //$anchorScroll();
                window.scrollTo(0, 0); // Scroll top
            }, 3000);

            $interval(getNewPosts, 10000);
        };

        function getPostsInit() {
            posts.getNewPosts(20, 0)
            .then(function(posts) {
                posts.push(...$scope.posts);
                $scope.posts = posts;

                $scope.postsLoaded = true;
                
                // Update $scope.newestPost
                for (let post of posts) {
                    post.id = post.id * 1.0;
                    if (post.id > $scope.newestPost.id) {
                        $scope.newestPost = post;
                    }
                }

                // Update $scope.oldestPost
                $scope.oldestPost = posts[0];
                for (let post of posts) {
                    if (post.id < $scope.oldestPost.id) {
                        $scope.oldestPost = post;
                    }
                }
            }, function(error) {
                console.log(error.reason);
            });
        }

        function getNewPosts() {
            posts.getNewPosts(20, $scope.newestPost.id)
            .then(function(posts) {
                posts.push(...$scope.posts);
                $scope.posts = posts;
                
                // Update $scope.newestPost
                for (let post of posts) {
                    post.id = post.id * 1.0;
                    if (post.id > $scope.newestPost.id) {
                        $scope.newestPost = post;
                    }
                }
            }, function(error) {
                console.log(error.reason);
            });
        }

    }]);