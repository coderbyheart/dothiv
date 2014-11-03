angular.module('dotHIVApp.services').factory('ContentBehaviour', [
    function () {
        return {
            run: function () {
                $('a').filter(function (index, a) {
                    var href = $(a).attr('href');
                    if (!href) {
                        return false;
                    }
                    return href.match('^(http|\/\/)') ? true : false;
                }).attr('target', '_blank');
            }
        }
    }
]);
