'use strict';

angular.module('dotHIVApp.controllers').controller('AdminNonProfitController', ['$scope', '$http', '$state', function ($scope, $http, $state) {

    $scope.registrations = [];
    $scope.loaded = false;
    $scope.nextUrl = null;
    $scope.prevUrl = null;

    function loadList(url) {
        $scope.loaded = false;
        $http.get(url).success(function (data) {
            $scope.registrations = data.items;
            $scope.loaded = true;
            $scope.nextUrl = data.nextPageUrl;
            $scope.prevUrl = data.prevPageUrl;
        });
    }

    $scope.edit = function (registration) {
        $state.transitionTo('=.nonprofit.edit', {'domain': registration.domain});
    }

    $scope.next = function (registration) {
        loadList($scope.nextUrl);
    }

    $scope.prev = function (registration) {
        loadList($scope.prevUrl);
    }

    loadList('/admin/api/nonprofitregistration');
}]);
