document.addEventListener("DOMContentLoaded",function() {

    var
        sortFilterId = parseInt(document.getElementById("sort-filter-id").value, 10),
        sortItem = document.querySelectorAll(".oip-filter-sort-item"),
        sortItemReset = document.getElementById("oip-filter-sort-reset");

    OIP.Store.init(sortFilterId, "Rating");
    OIP.Store.init(sortFilterId, "Recommend");
    OIP.Store.init(sortFilterId, "created");

    if(sortItem.length > 0) {

        OIP.Helpers.List.addEventListener(sortItem, "click", function () {
            var
                self = event.target,
                sortName = self.getAttribute("data-sort-name"),
                paramName = "f" + sortFilterId + "_s"  + sortName;

            for(var i = 0; i < sortItem.length; i++) {
                sortItem[i].closest("li").classList.remove("uk-active");
            }
            self.closest("li").classList.add("uk-active");

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
    }

    if(sortItemReset) {
        sortItemReset.addEventListener("click", function () {

            OIP.Store.unsetItem("f" + sortFilterId + "_sRating");
            OIP.Store.unsetItem("f" + sortFilterId + "_sRecommend");
            OIP.Store.unsetItem("f" + sortFilterId + "_screated");

            for(var i = 0; i < sortItem.length; i++) {
                sortItem[i].closest("li").classList.remove("uk-active");
            }

            OIP.Filter.apply(sortFilterId);
        })
    }
});
