'use strict';

angular.module('dotHIVApp.controllers').controller('AdminDashboardController', ['$scope', 'dothivAdminStatsResource', '$http', function ($scope, dothivAdminStatsResource, $http) {

    $scope.reporters = [];
    var loadedReports = {};

    $http.get('/admin/api/stats').success(function (data) {
        for (var n in data) {
            $http.get(data[n]['@id']).success(function (data) {
                $scope.reporters.push(data);
            });
        }
    });

    $scope.activateTab = function (report) {
        var url = report['@id'];
        if (typeof loadedReports[url] == "undefined") {
            loadedReports[url] = $http.get(url).success(function (data) {
                for (var k in data) {
                    if (data.hasOwnProperty(k)) {
                        report[k] = data[k];
                    }
                }
                report.loaded = true;
            });
        }
    }

}]);
