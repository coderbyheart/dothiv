'use strict';

angular.module('dotHIVApp.controllers').controller('AdminLoginController', ['$scope', 'dothivAdminResource', function ($scope, dothivAdminResource) {
    $scope.errorMessage = null;
    $scope.successMessage = null;
    $scope.loginForm = {};
    $scope.loading = false;
    $scope.done = false;

    function _submit() {
        $scope.loading = true;
        $scope.errorMessage = null;
        dothivAdminResource.requestLoginLink(
            $scope.loginForm,
            function () { // success
                $scope.done = true;
                $scope.loading = false;
            },
            function (response) { // error
                $scope.errorMessage = response.statusText;
                $scope.loading = false;
            }
        );
    }

    $scope.submit = _submit;
}]);
