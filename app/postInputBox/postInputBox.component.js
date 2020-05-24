(function() {
    'use strict';

    angular.module('fb')
    .component('postInputBox', {
        templateUrl: 'app/postInputBox/postInputBox.html',
        controllerAs: 'vm',
        controller: ['client', 'postInputBox', postInputBoxController]
    });

    function postInputBoxController(client, postInputBox) {
        var vm = this;

        vm.writePost = writePost;

        function writePost() {
            postInputBox.writePost(vm.content).then(
                function() {
                    vm.content = '';
                },
                function(error) {
                    alert("Write post failed because " + error.reason);
                });
        }
    }
})();