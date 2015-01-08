'use strict';

angular.module('dotHIVApp.controllers').controller('LookupResultController', [
    '$scope', '$state', '$stateParams', 'Price', '$http', 'idn', 'OrderModel', 'config',
    function ($scope, $state, $stateParams, Price, $http, idn, OrderModel, config) {
        $scope.loading = false;
        $scope.lookup = null;
        $scope.domain = $stateParams.domain;
        OrderModel.setDomain($stateParams.domain);
        $scope.secondLevel = $stateParams.domain.split('.hiv').join('');
        $scope.price = Price.getFormattedPricePerYear($scope.domain);
        $scope.pricePerMonth = Price.getFormattedPricePerMonth($scope.domain);


        var lookupDomain = function (domain) {
            $scope.loading = true;
            $scope.showLookupForm = false;
            $http.get('/api/shop/lookup?q=' + idn.toASCII(domain))
                .success(function (data) {
                    OrderModel.available = false;
                    if (data.available) {
                        $scope.lookup = "available";
                        OrderModel.available = true;
                    } else if (data.premium) {
                        $scope.lookup = "premium";
                        // } else if (data.trademark) {
                    } else { // if(data.registered) {
                        $scope.lookup = "registered";
                        var alternatives = [];
                        if ($stateParams.locale == 'de') {
                            alternatives.push($scope.secondLevel + '-gegen-aids.hiv');
                            alternatives.push($scope.secondLevel + '-sozial.hiv');
                            alternatives.push($scope.secondLevel + '-macht-mit.hiv');
                        } else {
                            alternatives.push($scope.secondLevel + 'supports.hiv');
                            alternatives.push($scope.secondLevel + 'fortheendofaids.hiv');
                            alternatives.push($scope.secondLevel + 'forhope.hiv');
                        }
                        $scope.alternatives = alternatives;
                    }
                    $scope.showLookupForm = true;
                })
                .error(function (response, code, headers, request) {
                    $scope.loading = false;
                    $scope.showLookupForm = true;
                })
            ;
        };

        // 4lifepromo
        $scope.promoAvailable = false;
        $scope.promoDomain = $scope.secondLevel + "4life.hiv";
        var lookupPromoDomain = function (domain) {
            $http.get('/api/shop/lookup?q=' + idn.toASCII(domain))
                .success(function (data) {
                    if (data.available) {
                        $scope.promoAvailable = true;
                        $scope.promoPrice = Price.getFormattedPricePerYear(domain);
                        $scope.promoPricePerMonth = Price.getFormattedPricePerMonth(domain);
                    }
                })
            ;
        };

        $scope.lookupDomain = function (domain) {
            $state.transitionTo('lookup', {"locale": $stateParams.locale, "domain": domain});
        };

        // Init
        lookupDomain($stateParams.domain);
        if ($stateParams.domain.indexOf("4life.hiv") < 0 && config.shop.promo.name4life) {
            lookupPromoDomain($scope.promoDomain);
        }
    }]);