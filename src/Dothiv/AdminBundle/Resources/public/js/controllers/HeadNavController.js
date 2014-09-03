'use strict';

angular.module('dotHIVApp.controllers').controller('AdminHeadNavController', ['$scope', '$location', '$state', 'security',
    function ($scope, $location, $state, security) {
        $scope.state = $state;
        $scope.logout = function () {
            security.logout();
            $state.transitionTo('login');
        };
        $scope.isLoggedIn = function () {
            return security.isAuthenticated();
        }
    }
]);
