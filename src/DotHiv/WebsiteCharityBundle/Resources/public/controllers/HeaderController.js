'use strict';

angular.module('dotHIVApp.controllers').controller('HeaderController', ['$scope', '$state', 'security', 'securityDialog', 'locale', '$rootScope',
    function($scope, $state, security, securityDialog, locale, $rootScope) {
        // make state information available
        $scope.state = $state;

        $scope.urlOffset = 0;
        $scope.urls = [
                       {'name': 'www.google.hiv' },
                       {'name': 'www.facebook.hiv' },
                       {'name': 'www.twitter.hiv' },
                       {'name': 'www.web.hiv' },
                       {'name': 'www.youtube.hiv' }
                   ];

        $scope.locale = locale;
        $scope.siteLanguages =
            [
                { key: 'de', label: 'Deutsch' },
                { key: 'en', label: 'Englisch' },
                { key: 'key',label: 'Keys only' },
            ];

        $scope.$watch('locale.language', function() {
            locale.set(locale.language);
        });

        $scope.isAuthenticated = function() {
            return security.isAuthenticated();
        };

        $scope.login = function() {
            securityDialog.showLogin('=.profile.summary');
        };

        $scope.logout = function() {
            security.logout();
        };

        $scope.security = security.state;

        $scope.bar = {
            'total': 10,
            'current': 6.43,
            'click': 29000,
        };

        $scope.showfunding = false;

        $scope.toggle = function() {
            $scope.showfunding = !$scope.showfunding;
        }

        $scope.$on('localeInitialized', function() {
            $scope.finishedbooting = true;
        });
    }
]);
