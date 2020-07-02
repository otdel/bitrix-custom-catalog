
$(function () {
    var
        filterId = $("#data-filter-id").val(),
        ajaxContainer = $("#oip-ajax-container");

    OIP.Store.init(filterId, "TAGS");

    ajaxContainer.on("click", ".oip-filter-tag-item", function () {
        var
            self = $(this),
            filterId = self.data("filter-id"),
            paramName = "f"+filterId+"_pTAGS",
            paramValue = self.data("tag-id");

        if(self.parent().hasClass("uk-active")) {
            return;
        }

        self.closest("li").addClass("uk-active");
        OIP.Store.setItem(paramName, paramValue, true);
        OIP.Filter.apply(filterId);
    });

    ajaxContainer.on("click", ".oip-filter-tag-item-reset", function () {
        var
            self = $(this),
            filterId = self.data("filter-id"),
            paramName = "f"+filterId+"_pTAGS",
            paramValue = self.data("tag-id");


        self.closest("li").removeClass("uk-active");
        OIP.Store.unsetItem(paramName, paramValue, true);

        OIP.Filter.apply(filterId);
    });
});