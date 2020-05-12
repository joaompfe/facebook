(function() {
    'use strict';

    angular.module('fb.utils')
        .directive('fbElement', fbElement)
        .directive('uploadPhoto', uploadPhoto)

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

    function uploadPhoto($parse) {
        return {
            link: function(scope, element, attrs){
                element.bind("change", function(event){
                    var files = event.target.files;
                    //console.log(files[0].name)
                    $parse(attrs.uploadPhoto).assign(scope, element[0].files);
                    scope.$apply();
                })
            }
        };
    }
})();