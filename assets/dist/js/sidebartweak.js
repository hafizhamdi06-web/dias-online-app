$.AdminLTESidebarTweak = {};

$.AdminLTESidebarTweak.options = {
    EnableRemember: true,
    NoTransitionAfterReload: false
};

$(function () {
    "use strict";

    function setCookie(value) {
        let name = 'toggleState';
        let days = 365;
        let d = new Date;
        d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
        document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
    }

    $("body").on("collapsed.pushMenu", function () {
        if ($.AdminLTESidebarTweak.options.EnableRemember) {
            setCookie('closed');
        }
    }).on("expanded.pushMenu", function () {
        if ($.AdminLTESidebarTweak.options.EnableRemember) {
            setCookie('opened');
        }
    });
});