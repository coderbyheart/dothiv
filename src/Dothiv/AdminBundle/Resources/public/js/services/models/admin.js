'use strict';

angular.module('dotHIVApp.services').factory('dothivAdminResource', ['$resource', function ($resource) {
    return $resource('/admin/api/account/:handle/:sub', {}, {
        'requestLoginLink': {method: 'POST', isArray: true, params: {handle: 'loginLink', sub: null}},
        'clearToken': {method: 'DELETE', params: {handle: '@handle', sub: 'token'}}
    });
}]);
