
define(function(){var a={},d=a.hasOwnProperty;return{subscribe:function(b,c){d.call(a,b)||(a[b]=[]);var e=a[b].push(c)-1;return{remove:function(){delete a[b][e]}}},publish:function(b,c){d.call(a,b)&&a[b].forEach(function(a){a(void 0!=c?c:{})})}}});
