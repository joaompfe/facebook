"use strict";

angular.module("fb.profile", []).factory("profile", [
	"$http",
	"$q",
	"utils",
	function ($http, $q, utils) {
		var profile = {
            getUserProfile: getUserProfile,
			changeProfilePhoto: changeProfilePhoto,
			getProfilePic: getProfilePicture
		};

		return profile;

		function getUserProfile(id) {
			return utils.httpPromisse(
				{
					url: "server/profile/profile.php",
					method: "GET",
					params: {
						id: id,
					},
				},
				"profile",
				"Server returned no profile"
			);
		}

		function getProfilePicture(id) {
			return utils.httpPromisse(
				{
					url: "server/profile/getProfilePic.php",
					method: "GET",
					params: {
						id: id,
					},
				},
				"profilePic",
				"Server returned no profile picture"
			);
		}

		function changeProfilePhoto(form_data) {
			return utils.httpPromisse(
				{
					url: "server/uploadImage.php",
					method: "POST",
					data: form_data,
					transformRequest: angular.identity,
					headers: {
						"Content-Type": undefined,
						"Process-Data": false,
					},
				},
				null,
				"Profile photo change failed in server"
            );
          
		}
	},
]);
