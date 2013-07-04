'use strict';

angular.module('dotHIVApp.controllers').controller('ProfileDomainController', ['$scope', 'security', 'dothivUserResource',
    function($scope, security, dothivUserResource) {
        // get personal list of domains from server
        $scope.domains = dothivUserResource.getDomains(
            {"username": security.state.user.username}
        );

        // set initial page length to 5 entries
        $scope.pageLength = 5;
    }
]);