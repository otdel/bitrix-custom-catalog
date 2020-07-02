$(function() {

    var
        ajaxFormContainer = $("#oip-ajax-filter-form-container"),
        filterId = $("#oip-filter-apply").data("filter-id");

     OIP.Store.init(filterId, "NAME");

    ajaxFormContainer.on("click", "#oip-filter-apply", function () {
        var
            target = $(this),
            filterId = target.data("filter-id");

        $(".oip-filter-simple-item").each(function (key, element) {

            var
                element = $(element),
                elementFilterId = element.data("filter-id");

            if(elementFilterId == filterId) {

                if(element.val()) {
                    OIP.Store.setItem(element.attr("name"), element.val(), false);
                }
                else {
                    OIP.Store.unsetItem(element.attr("name"), element.val(), false);
                }

            }
        });

        OIP.Filter.apply(filterId);
    });

    ajaxFormContainer.on("click", "#oip-filter-reset", function() {
        OIP.Store.unsetFilter(filterId);

        $(this).addClass("uk-invisible");
        OIP.Filter.apply($(this).data("filter-id"));
    });

    ajaxFormContainer.on("submit", ".oip-filter-form", function (event) {
        event.preventDefault();
    });

    ajaxFormContainer.on("keyup", ".oip-filter-simple-item", function (event) {
        if(event.code === "Enter") {

            var
                target = $(this),
                elementFilterId = target.data("filter-id");

            if(elementFilterId == filterId) {

                if(target.val()) {
                    OIP.Store.setItem(target.attr("name"),target.val(), false);
                }
                else {
                    OIP.Store.unsetItem(target.attr("name"), target.val(), false);
                }

            }

            OIP.Filter.apply(filterId);
        }
    });

});