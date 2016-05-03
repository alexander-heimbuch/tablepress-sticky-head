(function ($) {
    var repositionHead = function ($header, $wrapper) {
            var wrapperOffsetTop = $wrapper.offset().top,
                scrollOffsetTop = $('body').scrollTop(),
                relativeOffset = scrollOffsetTop - wrapperOffsetTop;

            if (scrollOffsetTop > wrapperOffsetTop + $wrapper.height() - $header.height()) {
                return;
            }

            if (relativeOffset < 0) {
                $header.css('top', 0);
                $header.removeClass('moving');
            } else {
                $header.addClass('moving');
                $header.css('top', relativeOffset);
            }
        },
        stickyHeader = function ($tableNode) {
            var $dataTable = $tableNode.DataTable(),
                settings = $dataTable.settings()[0],
                $wrapper,
                $header,
                $headerCopy;

            if (settings.nScrollHead === null) {
                $header = $tableNode.find('thead');
                $header.find('th').each(function (index, node) {
                    var $node = $(node);
                    $node.width($node.width());
                });

                $headerCopy = $header.clone();
                $headerCopy.insertAfter($header);
            } else {
                $header = $headerCopy = $(settings.nScrollHead);
            }

            $wrapper = $header.parent();
            $wrapper.css('position', 'relative');

            $headerCopy.addClass('stickyHeader');

            $(window).on('scroll', function () {
                repositionHead($headerCopy, $wrapper);
            });
        };
        
    $(document).ready(function () { 
        if (window.STICKY_HEAD === undefined) {
            return;
        }

        $.each(window.STICKY_HEAD, function (tableId) {
            var $table = $('#' + tableId);

            $table.on( 'draw.dt', function () {
                stickyHeader($table);
            });
        });
    });
})(jQuery);
