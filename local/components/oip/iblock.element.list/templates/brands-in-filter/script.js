document.addEventListener("DOMContentLoaded",function() {

    var
        filterId = document.getElementById("data-filter-id").value,
        brandItem = document.querySelectorAll(".oip-filter-brand-item"),
        brandsFilterApply = document.getElementById("oip-filter-brands-apply"),
        brandsFilterReset = document.getElementById("oip-filter-brands-reset"),
        brandSelectHandler = function (event) {

            var
                self = event.target,
                paramName = OIP.Filter.getCheckboxName(self.name),
                paramValue = OIP.Filter.getCheckboxValue(self.name),
                paramChecked = self.checked;


            if(paramChecked) {
                OIP.Store.setItem(paramName, paramValue, true);
                self.closest("li").classList.add("uk-active");
            }
            else {
                OIP.Store.unsetItem(paramName, paramValue, true);
                self.closest("li").classList.remove("uk-active");
            }
        };

    OIP.Store.init(filterId, "BRANDS");

    if(brandItem.length > 0) {
        OIP.Helpers.List.addEventListener(brandItem,"click", brandSelectHandler);
    }

    brandsFilterApply.addEventListener("click",function (event) {
        OIP.Filter.apply(event.target.getAttribute("data-filter-id"));
    });

    if(brandsFilterReset) {
        brandsFilterReset.addEventListener("click", function () {
            var
                container =  document.getElementById("oip-filter-brands-container"),
                items = container.getElementsByClassName("oip-filter-brand-item");

            OIP.Helpers.List.each(items, function (listItem) {
                var
                    paramName = OIP.Filter.getCheckboxName(listItem.name),
                    paramValue = OIP.Filter.getCheckboxValue(listItem.name);

                listItem.checked = false;
                listItem.closest("li").classList.remove("uk-active");

                OIP.Store.unsetItem(paramName, paramValue, true);
            });

            this.classList.add("uk-invisible");
            OIP.Filter.apply(this.getAttribute("data-filter-id"));
        });
    }
});