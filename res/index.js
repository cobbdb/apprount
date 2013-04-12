$(function () {
    // Resize search bar with window resize.
    $(window).resize(sizeSearchBar);
    sizeSearchBar();

    // Format query on form submit.
    $("form").submit(function () {
        // Commit any unentered title.
        addTitle();
        
        // Build search query.
        var query = "";
        $("#list span").each(function () {
            query += $(this).text() + "|";
        });
        query = query.slice(0, -1);
        
        // Populate hidden title field.
        $('input[type="hidden"]').val(query);
    });

    var pasteEvent = false;
    $("textarea")
        .on("paste", function () {
            pasteEvent = true;
        })
        .on("keyup", function () {
            if (pasteEvent) {
                pasteEvent = false;
                addTitle();
            }
        })
        .on("keypress", function (evt) {
            if (evt.which == 13) {
                addTitle();
                return false;
            }
        });
});


// Set width of search bar.
var sizeSearchBar = function () {
    var wTotal = $("form").outerWidth();
    var wDepth = $("#depth").outerWidth();
    $("#titles").width(wTotal - wDepth - 14);
};


// Commit current entries to query list.
function addTitle() {
    var val = $("textarea").val().trim();
    if (val != "") {
        // Tokenize by line break.
        var titles = $("textarea").val().split("\n");
        for (var i in titles) {
            var title = titles[i].trim();
            if (title == "") {
                continue;
            }
            $("#list").append(' \
<div> \
    <img src="res/img/icon_trash.png" title="Remove title"/> \
    <span>' + title + '</span> \
</div> \
            ');
            $("#list img").click(function () {
                $(this).parent().remove();
            });
        }
    }
    $("textarea")
        .val("")
        .text("");
};