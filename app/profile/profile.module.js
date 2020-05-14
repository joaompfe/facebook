"use strict";

angular.module("fb.profile", []).controller("ProfileCtrl", [
	"$scope",
    "$routeParams",
    "$location",
    "$route",
	"profile",
	"client",
	function ($scope, $routeParams, $location, $route, profile, client) {

		$scope.isClientProfile = ($routeParams.id == client.id);
		$scope.changeProfilePhoto = changeProfilePhoto;

		init();

		function init() {
            profile.getPerson($routeParams.id)
            .then(function (person) {
				$scope.person = person;
			}, function(error) {
                $location.url("/");
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