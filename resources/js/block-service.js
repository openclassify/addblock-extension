function getBlock(location, params = {}) {
    var html = "";
    $.ajax({
        type: 'POST',
        data: {'location': location, 'params': params},
        async: false,
        url: service_url,
        success: function (r) {
            html = r.html;
        },
    });

    return html;
}