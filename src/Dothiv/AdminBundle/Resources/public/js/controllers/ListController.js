'use strict';

angular.module('dotHIVApp.controllers').controller('AdminListController', ['$scope', '$http', '$state', '$window', 'ContentBehaviour', function ($scope, $http, $state, $window, ContentBehaviour) {

    $scope.listUrl = null;
    $scope.items = [];
    $scope.loaded = false;
    $scope.nextUrl = null;
    $scope.prevUrl = null;

    var loading = false;

    function loadList(url) {
        if (loading) {
            return;
        }
        loading = true;
        $scope.loaded = false;
        $http.get(url).success(function (data) {
            for (var i = 0; i < data.items.length; i++) {
                $scope.items.push(data.items[i]);
            }
            $scope.loaded = true;
            loading = false;
            $scope.nextUrl = data.nextPageUrl;
            $scope.prevUrl = data.prevPageUrl;
            $window.setTimeout(function () {
                ContentBehaviour.run();
                loadMore();
            }, 1);
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

    function loadMore() {
        if ($('#loadMoreButton').offset().top - $(window).scrollTop() < $(window).height()) {
            if ($scope.nextUrl) {
                loadList($scope.nextUrl);
            }
        }
    }

    $(window).scroll(function () {
        loadMore();
    });
}]);
