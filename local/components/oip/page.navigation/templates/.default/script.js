$(function() {
    var ajaxContainer = $("#oip-ajax-container");

    ajaxContainer.on("click", ".oip-page-apply", function (event) {

        if(OIP.State.isAjaxMode()) {
            event.preventDefault();

            var
                id = $(this).data("filter-id"),
                page = $(this).data("page");

            OIP.Filter.apply(id, page);
        }

    });
});