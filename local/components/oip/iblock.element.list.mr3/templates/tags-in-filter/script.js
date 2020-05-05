document.addEventListener("DOMContentLoaded",function() {
    var
        filterId = document.getElementById("data-filter-id").value,
        tagItems = document.querySelectorAll(".oip-filter-tag-item"),
        tagItemsReset = document.querySelectorAll(".oip-filter-tag-item-reset");


    OIP.Store.init(filterId, "TAGS");

    if(tagItems.length > 0) {
        OIP.Helpers.List.addEventListener(tagItems,"click", function () {
            var
                self = event.target,
                filterId = self.getAttribute("data-filter-id"),
                paramName = "f"+filterId+"_pTAGS",
                paramValue = self.getAttribute("data-tag-id");

            self.closest("li").classList.add("uk-active");
            OIP.Store.setItem(paramName, paramValue, true);
            OIP.Filter.apply(filterId);
        });
    }

    if(tagItemsReset.length > 0) {
        OIP.Helpers.List.addEventListener(tagItemsReset,"click", function () {
            var
                filterId = this.getAttribute("data-filter-id"),
                paramName = "f"+filterId+"_pTAGS",
                paramValue = this.getAttribute("data-tag-id");


            this.closest("li").classList.remove("uk-active");
            OIP.Store.unsetItem(paramName, paramValue, true);

            OIP.Filter.apply(filterId);
        });
    }

});