"use strict";

angular.module("fb.profile").controller("ProfileCtrl", [
	"$scope",
	"$routeParams",
	"$http",
	"profile",
	"client",
	function ($scope, $routeParams, $http, profile, client) {

		$scope.checkUser = function () {
			return $routeParams.id == client.id;
		};

		$scope.changeProfilePhoto = changeProfilePhoto;

		test();

		function test() {
			profile.getUserProfile($routeParams.id).then(function (profile) {
				$scope.profile = profile;
			});
			getProfilePic();
			console.log($scope);
		}

		function getProfilePic() {
			profile.getProfilePic($routeParams.id).then(function (profilePic) {
				$scope.profilePic = profilePic;
			});
		}

		function checkUser() {
			return $routeParams.id == client.id;
		}

		function changeProfilePhoto() {
			var form_data = new FormData();
			angular.forEach($scope.files, function (file) {
				form_data.append("file", file);
			});
			profile.changeProfilePhoto(form_data).then(function() {}, function(error) {console.log(error.reason)});
			getProfilePic();
		}
	

		function select() {
			$http.get("select.php");
		}
	},
]);
