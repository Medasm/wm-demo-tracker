window.Muscula = { settings: {
    logId: "6c2564eb-1aaa-4729-a5b7-96a8b1bcc145", suppressErrors: false, branding: 'none'
}};
(function () {
    var m = document.createElement('script');
    m.type = 'text/javascript';
    m.async = true;
    m.src = (window.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//musculahq.appspot.com/Muscula.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(m, s);
    window.Muscula.run = function (c) {
        eval(c);
        window.Muscula.run = function () {
        };
    };
    window.Muscula.errors = [];
    window.onerror = function () {
        window.Muscula.errors.push(arguments);
        return window.Muscula.settings.suppressErrors === undefined;
    }
})();
