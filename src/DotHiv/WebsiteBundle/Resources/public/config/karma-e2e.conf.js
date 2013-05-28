basePath = '../';

files = [
  ANGULAR_SCENARIO,
  ANGULAR_SCENARIO_ADAPTER,
  'test/e2e/**/*.js'
];

autoWatch = false;

browsers = ['Chrome'];

singleRun = true;

proxies = {
  '/': 'http://dothiv.bp/'
};

urlRoot = '/e2e/';

junitReporter = {
  outputFile: 'test_out/e2e.xml',
  suite: 'e2e'
};
