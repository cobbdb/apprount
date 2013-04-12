$(function () {
    // busy is a hack to fix double requests on index.html
    if (!busy) {
        // Make ajax call to wait for results.
        busy = true;
        $.ajax({
            url: "./",
            dataType: "json",
            type: "GET",
            data: {
                action: "report",
                key: $("#key").val()
            },
            cache: false,
            success: function (res) {
                $("#page").effect("slide", {
                    mode: "hide"
                }, "slow", function () {
                    $(this)
                        .html(res.html)
                        .effect("slide", {
                            direction: "right",
                            mode: "show"
                        }, "slow", function () {
                            $("#wiki").data("report", res.wiki);
                            $("#csv").data("report", res.csv);
                        });
                });
            },
            error: function (xhr) {
                console.error(xhr.status + " : " + xhr.statusText);
            }
        });
    }
});