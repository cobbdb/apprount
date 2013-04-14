$(function () {
    $(".category").toggle(
        function () {
            $(this).next().slideToggle();
            $(this).find("img").attr({
                src: "res/img/icon_minus.png"
            });
        },
        function () {
            $(this).next().slideToggle();
            $(this).find("img").attr({
                src: "res/img/icon_plus.png"
            });
        }
    );

    $(".result_url").click(function (e) {
        e.stopPropagation();
    });


    $("#header_div span").button({
        label: "Exports",
        icons: {
            secondary: "ui-icon-gear"
        }
    });

    $("#formats").buttonset();

    $("#copy").button({
        label: "Select",
        icons: {
            primary: "ui-icon-document"
        }
    });

    $("#copy").click(function () {
        $("textarea").select();
    });

    $("#download").button({
        label: "Download",
        icons: {
            primary: "ui-icon-arrowthickstop-1-s"
        }
    });        

    $("#menu").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        draggable: false,
        show: {
            effect: "drop",
            mode: "show",
            direction: "up"
        },
        hide: {
            effect: "drop",
            direction: "up"
        },
        title: "Export Menu",
        closeOnEscape: false,
        open: function () {
            $("textarea").outerWidth($("#menu").width());
            $("#wiki + label").click();
        }
    });

    $("#header_div span").click(function () {
        $("#menu").dialog("open");
    });

    $("#wiki + label").click(function () {
        var wiki = $("#wiki").data("report");
        $("textarea").text(wiki);
    });

    $("#csv + label").click(function () {
        var csv = $("#csv").data("report");
        $("textarea").text(csv);
    });

    $("#formats :radio").change(function () {
        updateLink();
    });
});

var updateLink = function () {
    $("#download").attr("href", function () {
        var link = "filegen.php";
        var data = "?content=" + $("textarea").text();
        var type = "&type=" + $("#formats :radio:checked").attr("id");
        return link + encodeURI(data) + type;
    });
};
