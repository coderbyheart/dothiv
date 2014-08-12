'use strict';

angular.module('dotHIVApp', ['dotHIVApp.services', 'dotHIVApp.controllers', 'ngRoute', 'ui.router'])
    .config(['$locationProvider', function ($locationProvider) {
        $locationProvider.hashPrefix('!');
    }])
    .config(['$interpolateProvider', function ($interpolateProvider) {
        $interpolateProvider.startSymbol('%%');
        $interpolateProvider.endSymbol('%%');
    }])
    .config(['$httpProvider', function ($httpProvider) {
        $httpProvider.defaults.headers.common.Accept = "application/json";
    }])
    // Configute routing and states
    .config(['$stateProvider', function ($stateProvider) {
        $stateProvider
            .state('login', {
                url: '',
                templateUrl: '/admin/app/login.html',
                controller: 'AdminLoginController'
            })
            .state('auth', {
                url: '/auth/:handle/:auth_token',
                templateUrl: '/admin/app/auth.html',
                controller: 'AdminAuthController'
            })
            .state('=', {
                abstract: true,
                template: '<div data-ui-view></div>'
            })
            .state('=.dashboard', {
                url: '/dashboard',
                templateUrl: '/admin/app/dashboard.html',
                controller: 'AdminDashboardController'
            })
            .state('=.nonprofit', {
                abstract: true,
                template: '<div data-ui-view></div>'
            })
            .state('=.nonprofit.list', {
                url: '/nonprofit',
                templateUrl: '/admin/app/nonprofit.html',
                controller: 'AdminNonProfitController'
            })
            .state('=.nonprofit.edit', {
                url: '/nonprofit/:domain',
                templateUrl: '/admin/app/nonprofit-edit.html',
                controller: 'AdminNonProfitEditController'
            })

        ;
    }])
    .run(['$rootScope', 'security', '$state', function ($rootScope, security, $state) {
        $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            if (toState.name.match('^=\.')) {
                // Get the current user when the application starts (in case they are still logged in from a previous session)
                security.updateUserInfo();
                security.schedule(function () {
                    if (!security.isAuthenticated()) {
                        event.preventDefault();
                        $state.transitionTo('login');
                    }
                });
            }
        });
    }])
;
angular.module('dotHIVApp.services', ['dotHIVApp.controllers', 'ui.router', 'ngResource', 'ngCookies']);
angular.module('dotHIVApp.controllers', ['ui.bootstrap']);

