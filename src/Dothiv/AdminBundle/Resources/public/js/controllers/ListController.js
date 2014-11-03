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
    $scope.filterTerm = null;
    $scope.filterProperties = {};
    $scope.filterPropertyValues = {};

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
        var q = "";
        if ($scope.filterTerm) {
            q += $scope.filterTerm;
        }
        var props = [];
        for (var prop in $scope.filterProperties) {
            if ($scope.filterProperties.hasOwnProperty(prop)) {
                if ($scope.filterProperties[prop]) {
                    props.push('@' + prop + '{' + $scope.filterProperties[prop] + '}');
                }
            }
        }
        if (props.length > 0) {
            if (q.length > 0) {
                q += " ";
            }
            q += props.join(" ");
        }
        if (q.length > 0) {
            config.params.q = q;
        }
        $http.get(url, config).success(function (data) {
            if (clear) {
                $scope.items = [];
            }
            if (data.items) {
                for (var i = 0; i < data.items.length; i++) {
                    $scope.items.push(data.items[i]);
                }
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

    var loadTimeout;
    $scope.loadAfter = function () {
        if (loadTimeout) {
            $window.clearTimeout(loadTimeout);
        }
        loadTimeout = $window.setTimeout($scope.load, 350);
    };

    $(window).scroll(function () {
        loadMore();
    });

    // Load typeahead filter properties
    $scope.fetchFilterPropertyValues = function (url, name, initial) {
        initial = typeof initial == 'undefined' ? true : false;

        $http.get(url).success(function (data) {
            if (data.items) {
                if (initial) {
                    $scope.filterPropertyValues[name] = data.items;
                } else {
                    for (var i = 0; i < data.items.length; i++) {
                        $scope.filterPropertyValues[name].push(data.items[i]);
                    }
                }
            }
            if (data.nextPageUrl) {
                $scope.fetchFilterPropertyValues(data.nextPageUrl, name, false);
            }
        });
    };

    $scope.filterPropertyTypeahead = function ($item, propertyName) {
        $scope.filterProperties[propertyName] = $item['@id'];
        $scope.load();
    };

    // Clear filters
    $scope.clearFilters = function()
    {
        $scope.filterTerm = null;
        $scope.filterProperties = {};
        $scope.load();
    };
}]);
