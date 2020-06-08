(function () {
	'use strict';

	angular.module("fb.findPersons").component("personReqComp", {
		templateUrl: "app/findPersons/personRequest/personRequest.html",
		controllerAs: "vm",
		bindings: {
			personRequest: '<'
		},
		controller: ["personRequests", personRequestController],
	});

	function personRequestController(personRequest) {

		var vm = this;

		vm.accepted = false;
		vm.denied = false;

		vm.deny = deny;
		vm.accept = accept;

		function accept() {
			

			personRequest.requestResponse('Accepted', vm.personRequest.id).then(
				function () {
					vm.accepted = true;
				},
				function (error) {
					console.log(error.reason);
				}
			);

		}

		function deny() {
			
			personRequest.requestResponse("Denied", vm.personRequest.id).then(
				function () {
					vm.accepted = true;
				},
				function (error) {
					console.log(error.reason);
				}
			);

		}
		
	}

})();
