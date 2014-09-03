'use strict';

angular.module('dotHIVApp.controllers').controller('AdminNonProfitEditController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {

    $scope.domain = $stateParams.domain;
    $scope.loaded = false;
    $scope.registration = null;
    $scope.saving = false;
    $scope.errorMessage = null;

    var load = function () {
        $scope.loaded = false;
        $http.get('/admin/api/nonprofitregistration/' + $scope.domain).success(function (data) {
            $scope.registration = data;
            $scope.loaded = true;
        });
    };
    load();

    $scope.patch = function (what) {
        $scope.saving = true;
        $scope.errorMessage = null;
        $http.put('/admin/api/nonprofitregistration/' + $scope.domain + '/' + what, $scope.registration[what])
            .success(function (data) {
                $scope.saving = false;
                load();
            })
            .error(function (response, code, headers, request) {
                $scope.saving = false;
                $scope.errorMessage = code;
            })
        ;
    }
}]);
