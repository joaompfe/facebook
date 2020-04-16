(function() {
    'use strict';

    angular.module('fb.utils')
        .directive('fbFocusIf', focusIf);

    function focusIf() {
        return {
            scope: { trigger: '=fbFocusIf' },
            link: function(scope, element) {
                console.log("directiva");
                scope.$watch('trigger', function(value) {
                    if (value) {
                        element[0].focus();
                    }
                })
            }
        };
    }
})();