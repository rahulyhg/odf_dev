function wr360QueryGetParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

jQuery(document).ready(function() {
    var popup360Elm = jQuery("#wr360PlayerId20");
    if (popup360Elm.length == 1) {
        var backgroundColor = wr360QueryGetParameterByName("background");
        if (backgroundColor && backgroundColor.length > 0)
            popup360Elm.css("background-color", backgroundColor);

        popup360Elm.rotator({
            licenseFileURL: wr360QueryGetParameterByName("lic"),
            graphicsPath: wr360QueryGetParameterByName("grphpath"),
            configFileURL: wr360QueryGetParameterByName("config"),
            rootPath: wr360QueryGetParameterByName("root"),
            googleEventTracking: wr360QueryGetParameterByName("analyt") === "true",
            viewName: wr360QueryGetParameterByName("viewname")
        });
    }
    else {
        if ((typeof(jQuery.fn.prettyPhoto) !== "undefined") && (typeof(getWR360PopupSkin) !== "undefined"))
            jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({animation_speed: 0, deeplinking: false, theme: getWR360PopupSkin() });
    }
});
