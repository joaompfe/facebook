(function() {
    'use strict'

    angular.module('fb.post')
        .component('postComp', {
            templateUrl: 'app/post/post.html',
            bindings: {
                post: '<'
            },
            controllerAs: 'vm',
            controller: ['$scope', postController]
        });

    function postController($scope) {
        var vm = this;
    }
})();