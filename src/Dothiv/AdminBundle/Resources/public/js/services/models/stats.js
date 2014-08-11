'use strict';

angular.module('dotHIVApp.services').factory('dothivAdminStatsResource', ['$resource', function ($resource) {
    return $resource('/admin/api/stats/:reporter/:reporter', {}, {
        'listReporters': {method: 'GET', isArray: true}
    });
}]);
