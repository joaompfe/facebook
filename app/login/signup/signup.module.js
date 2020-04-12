'use strict';

angular.module('login.signUp', [])
    .controller('SignUpCtrl', ['$scope', 'signUp', 'client', function($scope, signUp, client) {
        // This controller maybe leave this module. ?
        $scope.birthday = {};
        $scope.birthday.days = [];
        $scope.birthday.months = [];
        $scope.birthday.years = [];
        $scope.birthday.selectedDay;
        $scope.birthday.selectedMonth;
        $scope.birthday.selectedYear;
        $scope.gender = "F";
        $scope.signUpFailed = false;
        
        $scope.submit = submit;

        for (var i = 1; i <= 31; i++) {
            $scope.birthday.days.push(i);
        }
        for (var i = 1905; i <= new Date().getFullYear(); i++) {
            $scope.birthday.years.push(i);
        }
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for (var i = 0; i < 12; i++) {
            $scope.birthday.months.push({name: months[i], number: i + 1});
        }
        $scope.birthday.selectedDay = $scope.birthday.days[0];
        $scope.birthday.selectedMonth = $scope.birthday.months[0];
        $scope.birthday.selectedYear = $scope.birthday.years[0];

        function submit() {
            var birthdayString = $scope.birthday.selectedYear + "/" + $scope.birthday.selectedMonth.number + "/" + $scope.birthday.selectedDay;
            signUp($scope.firstName, $scope.lastName, $scope.email, $scope.password, birthdayString, $scope.gender)
                .then(function(response) {
                    client.login($scope.email, $scope.password);
                }, function(response) {
                    $scope.signUpFailed = true;
                });
        }
    }])
    .component('signUpComp', {
        templateUrl: 'app/login/signup/signup.html',
        controller: 'SignUpCtrl'
    });