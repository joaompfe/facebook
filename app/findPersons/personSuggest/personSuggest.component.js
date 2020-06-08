(function () {
	"use strict";

	angular.module("fb.findPersons").component("personSuggestionComp", {
		templateUrl: "app/findPersons/personSuggest/personSuggest.html",
		controllerAs: "vm",
		bindings: {
			friendSuggestion: "<",
		},
		controller: ['personSuggestions', personSuggestController],
	});

	function personSuggestController(personSuggestion) {
		
		var vm = this;

		vm.requestSent = false;

		vm.addPerson = addPerson;

		function addPerson() {
			console.log(vm.friendSuggestion.id);
			personSuggestion.addPerson(vm.friendSuggestion.id).then(
				function () {
					vm.requestSent = true;
				},
				function (error) {
					console.log(error.reason);
				}
			);
		}

		console.log(personSuggestion);
	}
})();
