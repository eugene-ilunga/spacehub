"use strict"
var disqus_config = function () {
  this.page.url = currentUrl;
  this.page.identifier = postDetailsId;
  this.page.title = postTitle;
  this.language = longCode;
};

(function () {
  var d = document, s = d.createElement('script');
  s.src = 'https://' + shortName + '.disqus.com/embed.js';
  s.setAttribute('data-timestamp', +new Date());
  (d.head || d.body).appendChild(s);
})();
