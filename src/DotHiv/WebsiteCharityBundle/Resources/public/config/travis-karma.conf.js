basePath = '../';

files = [
  JASMINE,
  JASMINE_ADAPTER,
  'app/js/vendor/angular/angular.js',
  'app/js/vendor/angular/angular-*.js',
  'test/lib/angular/angular-mocks.js',
  'app/js/vendor/angular-translate.js',
  'app/js/**/*.js',
  'test/unit/**/*.js'
];

autoWatch = false;

browsers = ['Firefox'];

singleRun = true;

junitReporter = {
  outputFile: 'test_out/unit.xml',
  suite: 'unit'
};