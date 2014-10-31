'use strict';

angular.module('dotHIVApp.controllers').controller('AdminListController', ['$scope', '$http', '$state', '$window', 'ContentBehaviour', function ($scope, $http, $state, $window, ContentBehaviour) {

    $scope.listUrl = null;
    $scope.items = [];
    $scope.loaded = false;
    $scope.nextUrl = null;
    $scope.prevUrl = null;
    $scope.sortField = null;
    $scope.sortDir = null;
    $scope.total = 0;

    var loading = false;

    function loadList(url, clear) {
        clear = typeof clear == 'undefined' ? false : clear;
        if (loading) {
            return;
        }
        loading = true;
        $scope.loaded = false;
        var config = {params: {}};
        if ($scope.sortField) {
            config.params.sortField = $scope.sortField;
        }
        if ($scope.sortDir) {
            config.params.sortDir = $scope.sortDir;
        }
        $http.get(url, config).success(function (data) {
            if (clear) {
                $scope.items = [];
            }
            for (var i = 0; i < data.items.length; i++) {
                $scope.items.push(data.items[i]);
            }
            $scope.loaded = true;
            $scope.total = data.total;
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

    $scope.load = function () {
        loadList($scope.listUrl, true);
    };

    $scope.$watch('$scope.listUrl', function () {
        $scope.load();
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
