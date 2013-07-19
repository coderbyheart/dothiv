/**
 * Used to enter values into dhInput-elements
 */
angular.scenario.dsl('dhInput', function() {
    var chain = {};
    var msie = parseInt((/msie (\d+)/.exec(navigator.userAgent.toLowerCase()) || [])[1], 10);
    var supportInputEvent = msie != 9;

    chain.enter = function(value, event) {
      return this.addFutureAction("dhInput '" + this.name + "' enter '" + value + "'", function($window, $document, done) {
        var input = $document.elements(this.name).filter(':input');
        input.val(value);
        input.trigger(event || (supportInputEvent ? 'input' : 'change'));
        done();
      });
    };

    chain.focus = function() {
      return this.addFutureAction("dhInput '" + this.name + "' focus", function($window, $document, done) {
        var input = $document.elements(this.name).filter(':input');
        input.focus();
        done();
      });
    };

    return function(name) {
        this.name = name;
        return chain;
      };
});
