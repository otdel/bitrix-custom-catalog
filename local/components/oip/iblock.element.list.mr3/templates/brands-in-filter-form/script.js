document.addEventListener("DOMContentLoaded",function() {

    var
        filterId = document.getElementById("data-filter-id").value,
        brandItem = document.querySelectorAll(".oip-filter-brand-item"),
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

});