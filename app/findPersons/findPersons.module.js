'use strict';

angular
	.module("fb.findPersons", [])
	.controller("AddPersonsCtrl", [
		"$scope",
		"personSuggestions",
		"personRequests",
		function ($scope, personSuggestions, personRequests) {
		
			$scope.personRequests = [];
			$scope.personSuggestions = [];

			load();

			function load() {
				personSuggestions.getpersonSuggestions().then(
					function (personSuggestions) {
						$scope.personSuggestions = personSuggestions;
					},
					function (error) {
						console.log(error.reason);
					}
				);
				personRequests.getPersonRequests().then(
					function (personRequests) {
						$scope.personRequests = personRequests;
					},
					function (error) {
						console.log(error.reason);
					}
				);
			}
		},
	]); 