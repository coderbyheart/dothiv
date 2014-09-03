'use strict';

angular.module('dotHIVApp.controllers').controller('AdminEditController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {

    $scope.stateParams = $stateParams;
    $scope.itemUrl = null;
    $scope.loaded = false;
    $scope.item = null;
    $scope.saving = false;
    $scope.errorMessage = null;

    var loadItem = function (url) {
        $scope.loaded = false;
        $http.get(url).success(function (data) {
            $scope.item = data;
            $scope.loaded = true;
        });
    };

    $scope.patch = function (what) {
        $scope.saving = true;
        $scope.errorMessage = null;
        $http.put($scope.itemUrl + '/' + what, $scope.item[what])
            .success(function (data) {
                $scope.saving = false;
                loadItem($scope.itemUrl);
            })
            .error(function (response, code, headers, request) {
                $scope.saving = false;
                $scope.errorMessage = code;
            })
        ;
    }

    $scope.$watch('$scope.itemUrl', function () {
        loadItem($scope.itemUrl);
    });
}]);
