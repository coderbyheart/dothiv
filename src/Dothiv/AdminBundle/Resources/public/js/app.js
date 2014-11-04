'use strict';

angular.module('dotHIVApp', ['dotHIVApp.services', 'dotHIVApp.directives', 'dotHIVApp.controllers', 'ngRoute', 'ui.router', 'angularMoment'])
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
        ;
        var sections = ['domain', 'nonprofit', 'config'];
        for (var k in sections) {
            $stateProvider
                .state('=.' + sections[k], {
                    abstract: true,
                    template: '<div data-ui-view></div>'
                })
                .state('=.' + sections[k] + '.list', {
                    url: '/' + sections[k],
                    templateUrl: '/admin/app/' + sections[k] + '.html',
                    controller: 'AdminListController'
                })
                .state('=.' + sections[k] + '.edit', {
                    url: '/' + sections[k] + '/:identifier',
                    templateUrl: '/admin/app/' + sections[k] + '-edit.html',
                    controller: 'AdminEditController'
                });
        }
    }])
    .run(['$rootScope', 'security', '$state', '$window', 'ContentBehaviour', function ($rootScope, security, $state, $window, ContentBehaviour) {
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
        $rootScope.$on('$viewContentLoaded', function (event, current, previous, rejection) {
            $window.setTimeout(function() {
                ContentBehaviour.run();
            }, 0);
        });
    }])
;
angular.module('dotHIVApp.services', ['dotHIVApp.controllers', 'ui.router', 'ngResource', 'ngCookies']);
angular.module('dotHIVApp.directives', []);
angular.module('dotHIVApp.controllers', ['ui.bootstrap']);

