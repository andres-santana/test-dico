
requirejs.config({
    baseUrl: './scripts/lib',
    paths: {
        app: '../../scripts/app',
        jquery: 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min'
        
    },
    urlArgs: "v=4",
    shim: {
        'app/nav': ['svg.min'],
        'app/pro': ['j']
    },
    map:{
      '*': { 'j': 'jquery' },
      'j': { 'jquery': 'jquery' }
    }
});

// Start the main app logic.
requirejs(['j', 'svg.min', 'app/pro', 'app/nav', 'app/prompts'],
function ($, svg, p, nav, pr) {
    p.init();
    nav.initUI(p);
    pr.initUI(p);
});