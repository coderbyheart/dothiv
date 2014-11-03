'use strict';

angular.module('dotHIVApp.controllers').controller('AdminLoginController', ['$scope', 'dothivAdminResource', 'error', function ($scope, dothivAdminResource, error) {
    $scope.loading = false;
    $scope.done = false;

    function _submit() {
        $scope.loading = true;
        dothivAdminResource.requestLoginLink(
            $scope.login,
            function () { // success
                $scope.done = true;
                $scope.loading = false;
            },
            function (response) { // error
                error.show('Login failed', response.statusText);
                $scope.loading = false;
            }
        );
    }

    $scope.submit = _submit;
}]);
