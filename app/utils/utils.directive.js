(function() {
    'use strict';

    angular.module('fb.utils')
        .directive('fbElement', fbElement);

    function fbFocusIf() {
        return {
            scope: { trigger: '=fbFocusIf' },
            link: function(scope, element) {
                scope.$watch('trigger', function(value) {
                    if (value) {
                        element[0].focus();
                        scope.trigger = false;
                    }
                })
            }
        };
    }

    function fbElement() {
        return {
            scope: { elementHandler: '=fbElement' },
            link: function(scope, element) {
                scope.elementHandler = element[0];
                scope.$evalAsync(function() {
                    element[0].focus();
                });
            }
        };
    }
})();