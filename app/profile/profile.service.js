"use strict";

angular.module("fb.profile")
    .factory("profile", ['server', function (server) {
		var profile = {
            getPerson: getPerson,
			changeProfilePhoto: changeProfilePhoto,
            getProfilePic: getProfilePicture,
            getNewPosts: getNewPosts
		};

        const baseUrl = 'server/profile/';

        return profile;

		function getPerson(id) {
			return server.httpPromisse(
				{
					url: "server/person.php",
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
			return server.httpPromisse(
				{
					url: baseUrl + "getProfilePic.php",
					method: "GET",
					params: {
						id: id,
					},
				},
				"profilePic",
				"Server returned no profile picture"
			);
		}

		function changeProfilePhoto(formData) {
			return server.httpPromisse(
				{
					url: baseUrl + "changeProfilePic.php",
					method: "POST",
					data: formData,
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
        
        function getNewPosts(quantity, sincePostId, personId) {
            return server.httpPromisse(
                {
                    url: baseUrl + 'posts.php', 
                    method: 'GET',
                    params: {
                        quantity: quantity,
                        type: 'new',
                        postId: sincePostId,
                        id: personId
                    }
                },
                "posts",
                "Server returned no post"
            );
        }
	},
]);
