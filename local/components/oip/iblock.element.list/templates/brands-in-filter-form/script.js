$(function() {
    var
        ajaxFormContainer = $("#oip-ajax-filter-form-container"),
        filterId = $("#data-form-filter-id");

    OIP.Store.init(filterId, "BRANDS");

    ajaxFormContainer.on("click", ".oip-filter-brand-item", function() {
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
});