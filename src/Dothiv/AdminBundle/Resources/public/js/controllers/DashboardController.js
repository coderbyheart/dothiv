'use strict';

angular.module('dotHIVApp.controllers').controller('AdminDashboardController', ['$scope', 'dothivAdminStatsResource', '$http', '$q', function ($scope, dothivAdminStatsResource, $http, $q) {

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

    /**
     *                 data
     var reporter = data;
     var reports = [];
     for (var n in reporter.reports) {
                    reports.push($http({method: 'GET', url: reporter.reports[n]['@id']}));
     }
     reporter.reports = reports;
     */
}]);
