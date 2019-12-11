document.addEventListener("DOMContentLoaded",function() {

    var
        container = document.getElementById("oip-section-filter"),
        filterId = container.getAttribute("data-filter-id");

    OIP.Store.init(filterId, "SECTION_ID");

    container.addEventListener("change", function (event) {
        var
            name = event.target.name,
            value = event.target.value;

        if(value) {
            OIP.Store.setItem(name, value);
        }
        else {
            OIP.Store.unsetItem(name, value);
        }

    });
});