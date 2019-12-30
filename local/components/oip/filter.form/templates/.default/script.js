document.addEventListener("DOMContentLoaded",function() {

    var
        oipFilterApply = document.getElementById("oip-filter-apply"),
        oipFilterForm = document.querySelectorAll(".oip-filter-form"),
        filterId = oipFilterApply.getAttribute("data-filter-id"),
        oipFilterSimpleItem = document.querySelectorAll(".oip-filter-simple-item"),
        filterReset = document.getElementById("oip-filter-reset")
    ;

    OIP.Store.init(filterId, "NAME");

    oipFilterApply.addEventListener("click",function (event) {

        var filterId = event.target.getAttribute("data-filter-id");

        if(oipFilterSimpleItem.length > 0) {
            OIP.Helpers.List.each(oipFilterSimpleItem, function (element) {

                var elementFilterId = element.getAttribute("data-filter-id");

                if(elementFilterId == filterId) {

                    if(element.value) {
                        OIP.Store.setItem(element.name, element.value, false);
                    }
                    else {
                        OIP.Store.unsetItem(element.name, element.value, false);
                    }

                }
            })
        }

        OIP.Filter.apply(filterId);
    });

    if(filterReset) {
        filterReset.addEventListener("click", function () {
            OIP.Store.unsetFilter(filterId);

            this.classList.add("uk-invisible");
            OIP.Filter.apply(this.getAttribute("data-filter-id"));
        });
    }

    OIP.Helpers.List.addEventListener(oipFilterForm, "submit", function (event) {
        event.preventDefault();
    });

    OIP.Helpers.List.addEventListener(oipFilterSimpleItem, "keyup", function (event) {

        if(event.code === "Enter") {

            var
                elementFilterId = event.target.getAttribute("data-filter-id");

            if(elementFilterId == filterId) {

                if(event.target.value) {
                    OIP.Store.setItem(event.target.name, event.target.value, false);
                }
                else {
                    OIP.Store.unsetItem(event.target.name, event.target .value, false);
                }

            }

            OIP.Filter.apply(filterId);
        }

    });
});