$(function() {
    var
        ajaxContainer = $("#oip-ajax-container"),
        sortFilterId = parseInt($("#sort-filter-id").val());

    OIP.Store.init(sortFilterId, "Rating");
    OIP.Store.init(sortFilterId, "Recommend");
    OIP.Store.init(sortFilterId, "created");

    ajaxContainer.on("click", ".oip-filter-sort-item", function () {
        var
            self = $(this),
            sortName = self.data("sort-name"),
            paramName = "f" + sortFilterId + "_s"  + sortName;

        if(self.parent().hasClass("uk-active")) {
            return;
        }

        $(".oip-filter-sort-item").each(function () {
            $(this).removeClass("uk-active");
        });

        self.closest("li").addClass("uk-active");

        switch(sortName) {
            case "Rating":
                OIP.Store.unsetItem("f" + sortFilterId + "_sRecommend");
                OIP.Store.unsetItem("f" + sortFilterId + "_screated");
                break;

            case "Recommend":
                OIP.Store.unsetItem("f" + sortFilterId + "_sRating");
                OIP.Store.unsetItem("f" + sortFilterId + "_screated");
                break;

            case "created":
                OIP.Store.unsetItem("f" + sortFilterId + "_sRating");
                OIP.Store.unsetItem("f" + sortFilterId + "_sRecommend");
                break;
        }

        // на сайте сейчас направление сортировки не подразумевает asc
        OIP.Store.setItem(paramName, "desc");
        OIP.Filter.apply(sortFilterId);
    });

    ajaxContainer.on("click", "#oip-filter-sort-reset", function () {

        OIP.Store.unsetItem("f" + sortFilterId + "_sRating");
        OIP.Store.unsetItem("f" + sortFilterId + "_sRecommend");
        OIP.Store.unsetItem("f" + sortFilterId + "_screated");

        $(".oip-filter-sort-item").each(function () {
            $(this).removeClass("uk-active");
        });

        OIP.Filter.apply(sortFilterId);
    });
});
