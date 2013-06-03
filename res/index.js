$(function () {
    // Resize search bar with window resize.
    $(window).resize(sizeSearchBar);
    sizeSearchBar();

    // Format query on form submit.
    $('form').submit(function () {
        // Commit any unentered title.
        addTitle();

        // Build search query.
        var query = '';
        $('#list span').each(function (i, title) {
            query += $(title).text() + '|';
        });
        query = query.slice(0, -1);

        // Populate hidden title field.
        $('input[type="hidden"]').val(query);
    });

    var pasteEvent = false;
    $('textarea')
        .on('paste', function () {
            pasteEvent = true;
        })
        .on('keyup', function () {
            if (pasteEvent) {
                pasteEvent = false;
                addTitle();
            }
        })
        .on('keypress', function (evt) {
            if (evt.which == 13) {
                addTitle();
                return false;
            }
        });
});

// Set width of search bar.
var sizeSearchBar = function () {
    var wTotal = $('form').outerWidth();
    var wDepth = $('#depth').outerWidth();
    $('#titles').width(wTotal - wDepth - 14);
};

// Commit current entries to query list.
function addTitle() {
    var searchInput = $('textarea').val().trim();
    if (!_(searchInput).isEmpty()) {
        // Tokenize by line break.
        var titles = $('textarea').val().split('\n');
        // Create the line item template.
        var tpl = _.template($('#titleTpl').text());

        _(titles).each(function (title) {
            title = title.trim();
            if (!_(title).isEmpty()) {
                $('#list')
                    .prepend(tpl({
                        title: title
                    }))
                    .find('img')
                    .click(function (e) {
                        $(e.target).parent().remove();
                    });
            }
        });
    }
    $('textarea')
        .val('')
        .text('');
};
