"use strict";

angular.module("fb.profile", []).controller("ProfileCtrl", [
	"$scope",
    "$routeParams",
    "$location",
	"profile",
	"client",
	function ($scope, $routeParams, $location, profile, client) {

		$scope.isClientProfile = ($routeParams.id == client.id);
        $scope.changeProfilePhoto = changeProfilePhoto;
        $scope.posts = [];          // Defined in init()   
        $scope.postsLoaded = false;
        $scope.newestPost;          // Defined in init()
        $scope.oldestPost;          // Defined in init()

		init();

		function init() {
            profile.getPerson($routeParams.id)
            .then(function (person) {
				$scope.person = person;
			}, function(error) {
                $location.url("/");
            });

            profile.getNewPosts(20, 0, $routeParams.id)
            .then(function(posts) {
                posts.push(...$scope.posts);
                $scope.posts = posts;

                $scope.postsLoaded = true;
                
                $scope.newestPost = posts[0];
                $scope.oldestPost = posts[posts.length-1];
            }, function(error) {
                console.log(error.reason);
            });
        }
        
		function changeProfilePhoto() {
			var formData = new FormData();
			angular.forEach($scope.files, function (file) {
				formData.append("file", file);
            });
            
            profile.changeProfilePhoto(formData)
            .then(function() {
                document.getElementById('ProfileImgpng').src = 
                "server/profile/getProfilePic.php?id=" + $scope.person.id + 
                "&gender=" + $scope.person.gender + "&size=L#" + new Date().getTime();
            }, function(error) { 
                console.log(error.reason); 
            });
		}
	
	},
]);