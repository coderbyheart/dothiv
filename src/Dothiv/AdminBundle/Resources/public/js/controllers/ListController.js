'use strict';

angular.module('dotHIVApp.controllers').controller('AdminListController', ['$scope', '$http', '$state', function ($scope, $http, $state) {

    $scope.listUrl = null;
    $scope.items = [];
    $scope.loaded = false;
    $scope.nextUrl = null;
    $scope.prevUrl = null;

    function loadList(url) {
        $scope.loaded = false;
        $http.get(url).success(function (data) {
            $scope.items = data.items;
            $scope.loaded = true;
            $scope.nextUrl = data.nextPageUrl;
            $scope.prevUrl = data.prevPageUrl;
        });
    }

    $scope.edit = function (target, identifier) {
        $state.transitionTo(target, {identifier: identifier});
    };

    $scope.next = function () {
        loadList($scope.nextUrl);
    };

    $scope.prev = function () {
        loadList($scope.prevUrl);
    };

    $scope.$watch('$scope.listUrl', function () {
        loadList($scope.listUrl);
    });
}]);
