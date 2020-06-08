'use strict';

angular.module('fb.findPersons')
	.factory('personRequests', ['server', function (server) {
		
		var personRequests = {
			getPersonRequests: getPersonRequests,
			requestResponse: requestResponse
		}

		function getPersonRequests() {
			return server.httpPromisse(
				{
					url: "server/persons/personRequests.php",
					method: "GET"
				},
				"personRequests",
				"Server returned no person requests"
			)
		}

		function requestResponse(requestResponse, personID) {
			return server.httpPromisse(
				{
					url: "server/persons/responseFriendRequest.php",
					method: "POST",
					params: {
						requestResponse: requestResponse,
						personID: personID,
					},
				},
				null,
				"Error on response to person request"
			);
		}

		return personRequests;

	}])
	.factory('personSuggestions', ['server', function (server) {
		var personSuggestions = {
			getpersonSuggestions: getpersonSuggestions,
			addPerson: addPerson
		};
		function getpersonSuggestions() {
			return server.httpPromisse(
				{
					url: "server/persons/personSuggestion.php",
					method: "GET",
				},
				"personSuggestions",
				"Server returned no friend Sugestions"
			);
		}

		return personSuggestions;  

		function addPerson(personID){
			return server.httpPromisse(
				{
					url: "server/persons/sendFriendRequest.php",
					method: "POST",
					params: {
						personID: personID,
					},
				},
				null,
				"Send request failed"
			);
		}
	}])