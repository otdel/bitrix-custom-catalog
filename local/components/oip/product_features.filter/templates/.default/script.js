
$(function () {
    var
        filterId = $("#data-filter-pfeatures-id").val() ,
        ajaxContainer = $("#oip-ajax-container");

    OIP.Store.init(filterId, "PFEATURES");

    ajaxContainer.on("click", ".oip-filter-pfeature-item", function () {
        var
            self = $(this),
            //filterId = self.data("filter-id"),
            // paramName = "f"+filterId+"_PF",
            paramName = self.attr('name'),
            paramValue = self.val();

        if (self.is(":checked")) {
            OIP.Store.setItem(paramName, paramValue, false);
            window.history.replaceState('', '', updateURLParameter(window.location.href, paramName, paramValue));
        }
        else {
            OIP.Store.unsetItem(paramName, paramValue, false);
            window.history.replaceState('', '', updateURLParameter(window.location.href, paramName, null));
        }

        //OIP.Filter.apply(filterId);
    });

    $('#oip-ajax-container .oip-filter-pfeature-item-range').each(function() {
        var elem = $(this);
        elem.data('oldVal', elem.val());
        elem.bind("propertychange change click keyup input paste", function(event){
            if (elem.data('oldVal') != elem.val()) {
                elem.data('oldVal', elem.val());
                OIP.Store.setItem(elem.prop("name"), elem.val(), false);
                window.history.replaceState('', '', updateURLParameter(window.location.href, elem.prop("name"), elem.val()));
                // При установке хотя бы одной границ диапазона, устанавливать автоматом вторую
                var pairRangeFilter = "";
                var pairRangeFilterValue = -1;
                if (elem.prop("name").includes("[min]")) {
                    pairRangeFilter = elem.prop("name").replace("[min]", "[max]");
                    // Узнаем текущее значение парного инпута. Если не нашли (такого не должно быть, но все же) - ставим min значение текущего инпута
                    var pairInput = $('input[name="' + pairRangeFilter + '"]');
                    if (pairInput) {
                        pairRangeFilterValue = pairInput.val();
                    }
                    else {
                        pairRangeFilterValue = elem.prop("max");
                    }
                }
                else if (elem.prop("name").includes("[max]")) {
                    pairRangeFilter = elem.prop("name").replace("[max]", "[min]");

                    // Узнаем текущее значение парного инпута. Если не нашли (такого не должно быть, но все же) - ставим min значение текущего инпута
                    var pairInput = $('input[name="' + pairRangeFilter + '"]');
                    if (pairInput) {
                        pairRangeFilterValue = pairInput.val();
                    }
                    else {
                        pairRangeFilterValue = elem.prop("min");
                    }
                }
                if (pairRangeFilter !== "" && pairRangeFilterValue >= 0) {
                    OIP.Store.setItem(elem.prop("name"), elem.val(), false);
                    window.history.replaceState('', '', updateURLParameter(window.location.href, pairRangeFilter, pairRangeFilterValue));
                }
            }
        });
    });

    ajaxContainer.on("click", ".oip-filter-pfeatures-apply", function () {
        resetPageParameter();
        OIP.Filter.apply(filterId);
    });

    ajaxContainer.on("click", ".oip-filter-pfeatures-reset", function () {
        var obj = OIP.Store.getStore(); Object.keys(obj).forEach(key => {
            if (key.startsWith("pf")) {
                //OIP.Store.unsetFilter(key);
                OIP.Store.unsetItem(key, obj[key], false);
                window.history.replaceState('', '', updateURLParameter(window.location.href, key, null));
                OIP.Filter.apply();
            }
        });

        // Чекбоксы
        $('#oip-ajax-container .oip-filter-pfeature-item').each(function() {
            var elem = $(this);
            if (elem.is(":checked")) {
                elem.prop( "checked", false);
            }
        });
        // Диапазоны
        $('#oip-ajax-container .oip-filter-pfeature-item-range').each(function() {
            var elem = $(this);
            if (elem.prop("name").includes("[min]")) {
                elem.val(parseInt(elem.attr('min')));
            }
            else if (elem.prop("name").includes("[max]")) {
                elem.val(parseInt(elem.attr('max')));
            }
        });

        resetPageParameter();

        // Перезагружаем страницу, с удаленным набором параметров
        // TODO: В AJAX режиме следует перегружать часть страницы по-другому
        window.location.href = window.location.href;
    });

    function updateURLParameter(url, param, paramVal){
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (var i=0; i<tempArray.length; i++){
                if(tempArray[i].split('=')[0] != param){
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }

        var rows_txt = "";
        if (paramVal != null) {
            rows_txt = temp + "" + param + "=" + paramVal;
        }
        return baseURL + "?" + newAdditionalURL + rows_txt;
    }

    function resetPageParameter() {
        var pageParameter = window.location.search.match(/(page_\d+)=\d+/);
        if (pageParameter) {
            window.history.replaceState('', '', updateURLParameter(window.location.href, pageParameter[1], null));
        }
    }
});