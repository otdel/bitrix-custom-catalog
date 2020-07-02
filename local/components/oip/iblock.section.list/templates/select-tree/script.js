$(function() {
    var
        ajaxFormContainer = $("#oip-ajax-filter-form-container"),
        filterId = $("#oip-section-filter").data("filer-id");

    OIP.Store.init(filterId, "SECTION_ID");

    ajaxFormContainer.on("change", "#oip-section-filter", function () {
        var
            name = $(this).attr("name"),
            value = $(this).val();

        if(value) {
            OIP.Store.setItem(name, value);
        } else {
            OIP.Store.unsetItem(name, value);
        }

    });
});