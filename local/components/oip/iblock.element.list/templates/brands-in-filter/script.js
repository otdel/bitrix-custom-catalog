$(function() {
    var
        filterId = $("#data-filter-id"),
        ajaxContainer = $("#oip-ajax-container");

    OIP.Store.init(filterId, "BRANDS");

    ajaxContainer.on("click", ".oip-filter-brand-item", function() {
        var
            self = $(this),
            paramName = OIP.Filter.getCheckboxName(self.attr("name")),
            paramValue = OIP.Filter.getCheckboxValue(self.attr("name")),
            paramChecked = self.attr("checked");


        if(paramChecked) {
            OIP.Store.setItem(paramName, paramValue, true);
            self.closest("li").addClass("uk-active");
        }
        else {
            OIP.Store.unsetItem(paramName, paramValue, true);
            self.closest("li").removeClass("uk-active");
        }
    });

    ajaxContainer.on("click", "#oip-filter-brands-apply", function () {
        var self = $(this);

        OIP.Filter.apply(self.data("filter-id"));
    });

    ajaxContainer.on("click", "#oip-filter-brands-reset", function () {
        var
            self = $(this),
            container =  $("#oip-filter-brands-container"),
            items = container.find(".oip-filter-brand-item");

        items.each(function(key, item) {
            var
                current = $(item),
                paramName = OIP.Filter.getCheckboxName(current.attr("name")),
                paramValue = OIP.Filter.getCheckboxValue(current.attr("name"));

            current.prop("checked", false);
            current.closest("li").removeClass("uk-active");

            OIP.Store.unsetItem(paramName, paramValue, true);
        });

        self.addClass("uk-invisible");
        OIP.Filter.apply(self.data("filter-id"));
    });
});