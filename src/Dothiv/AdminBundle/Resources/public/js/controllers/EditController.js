'use strict';

angular.module('dotHIVApp.controllers').controller('AdminEditController', ['$scope', '$http', '$state', '$stateParams', '$window', 'ContentBehaviour', function ($scope, $http, $state, $stateParams, $window, ContentBehaviour) {

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
            $window.setTimeout(function () {
                ContentBehaviour.run();
            }, 1);
        });
    };

    $scope.patch = function (what) {
        var data = {};
        data[what] = $scope.item[what];
        $scope.saving = true;
        $scope.errorMessage = null;
        $http({method: 'PATCH', url: $scope.itemUrl, data: angular.toJson(data)})
            .success(function (data) {
                $scope.saving = false;
                loadItem($scope.itemUrl);
            })
            .error(function (response, code, headers, request) {
                $scope.saving = false;
                $scope.errorMessage = code;
            })
        ;
    };

    $scope.$watch('$scope.itemUrl', function () {
        loadItem($scope.itemUrl);
    });
}]);
