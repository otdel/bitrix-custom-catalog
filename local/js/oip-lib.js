var OIP  = (function () {

    const oipCatalogFilterName = "oipCatalogFilter";
    const sessionMode = true;
    const ajaxMode = true;
/* ---------------------------------------------------------------------------------------- */
    var
        data = {},

/* ---------------------------------------------------------------------------------------- */
        isAjaxMode = function() {
            return ajaxMode || sessionMode;
        },
/* ---------------------------------------------------------------------------------------- */
        objectIsEmpty = function(object) {
            return (JSON.stringify(object) == "{}") ? true : false;
        },

       arrayRemoveByValue = function(array, value) {
           var idx = array.indexOf(value);

           if (idx != -1) {
               var newArray = array;
               newArray.splice(idx, 1);
               return newArray;
           }
           return false;
       },

       listAddEventListener = function(list, event_name, callback, useCapture) {
            for (var i = 0; i < list.length; i++)
            {
                list[i].addEventListener(event_name, callback, useCapture);
            }
        },

       listEach = function(list, callback) {
            for (var i = 0; i < list.length; i++) {
                callback(list[i], i);
            }
        },

/* ---------------------------------------------------------------------------------------- */
        getCheckboxName = function(string) {
            arParams = string.split("_");
            return arParams[0]+"_"+arParams[1];
        },

        getCheckboxValue = function(string) {
            return arParams = string.split("_")[2];
        },

        applyFilter = function(filterId, page = null) {

            if(filterId) {

                var
                    url = location.pathname;
                    store = getStore(),
                    params = getParams(),
                    concat = mergeParams(store, params, filterId),
                    newSearch = encodeGetParams(concat),
                    redirect = (newSearch) ? url+"?"+newSearch : url;

                    if(page) {
                        redirect = (newSearch) ? url + "?" + newSearch + "&page_" + filterId + "=" + page
                            : url + "?page_" + filterId + "=" + page;
                    }

                if(ajaxMode || sessionMode) {
                    sessionStorage.setItem(oipCatalogFilterName, (newSearch) ? newSearch : "");

                    if(ajaxMode) {
                        applyXhrFilter(redirect);
                    }
                    else {
                        location.replace(redirect);
                    }
                }
                else {
                    location.replace(redirect);
                }
            }

            return false;
        },

        applyXhrFilter = function(redirect) {
            xhr = new XMLHttpRequest();

            xhr.open("GET", redirect);
            xhr.responseType = "document";
            xhr.send();

            document.getElementById("oip-ajax-loader").classList.add("active");
            document.getElementById("oip-ajax-form-loader").classList.add("active");

            xhr.onload = function() {
                var
                    oldContent = document.getElementById("oip-ajax-inner"),
                    newContent = xhr.response.getElementById("oip-ajax-inner"),
                    ajaxContainer = document.getElementById("oip-ajax-container"),

                    oldFilterForm = document.getElementById("oip-ajax-filter-form-inner"),
                    newFilterForm = xhr.response.getElementById("oip-ajax-filter-form-inner"),
                    ajaxFilterFormContainer = document.getElementById("oip-ajax-filter-form-container");

                ajaxContainer.replaceChild(newContent, oldContent);
                ajaxFilterFormContainer.replaceChild(newFilterForm, oldFilterForm);

                document.getElementById("oip-ajax-loader").classList.remove("active");
                document.getElementById("oip-ajax-form-loader").classList.remove("active");
            }

        //  @todo возможно, стоит перенести вставку результирующей верстки в вызывающие данный метод скрипты
        // @todo если встанет задача сделать ajax-подгрузку в несколько независимых блоков на странице, начать стоит с этого

        },

/* ---------------------------------------------------------------------------------------- */

        initStore = function(filterId, paramNames = null) {
            if(!filterId) return false;

            if(ajaxMode || sessionMode) {
                sessionStorage.setItem(oipCatalogFilterName, "");
            }

            var
                params = getParams(),
                initAction = function(name, value) {
                    values = value.split(",");
                    for (var i = 0; i < values.length; i++) {
                        setStoreItem(name, values[i], true);
                    }
                };


            for (name in params) {

                paramMap = getParamMap(name);


                if(paramNames && typeof paramNames  == "object") {

                    if(filterId == paramMap.filterId && paramNames.indexOf(paramMap.paramName) > -1) {
                        initAction(name, params[name]);
                    }

                }
                else if(paramNames && typeof paramNames  != "object") {
                    if(filterId == paramMap.filterId && paramNames == paramMap.paramName) {
                        initAction(name, params[name]);
                    }
                }
                else {

                    if(filterId == paramMap.filterId) {
                        initAction(name, params[name]);
                    }
                }
            }
        },

        getStore = function() {
            return Object.assign({}, data);
        },

        getStoreItem = function (name) {
            return data[name];
        },

        setStoreItem = function(name, value, multiFlag = false) {

            if(!multiFlag) {
                data[name] = value;
            }
            else {
                if(!data.hasOwnProperty(name)) {
                    data[name] = [];
                    data[name].push(value);
                }
                else {
                    data[name].push(value);
                }
            }

            return data[name];
        },

        unsetStoreItem = function (name, value, multiFlag = false) {

            if(!multiFlag) {
                delete data[name];
                return (!data.hasOwnProperty(name));
            }
            else {
                if(!data.hasOwnProperty(name)) {
                    return true;
                }
                else if(!data[name].length) {
                    delete data[name];
                    return (!data.hasOwnProperty(name));
                }
                else {
                    data[name] = arrayRemoveByValue(data[name],value);

                    if(!data[name].length) {
                        delete data[name];
                        return (!data.hasOwnProperty(name));
                    }
                }
            }
        },

        unsetStoreFilter = function($filterKey) {
            var store = getStore();

            for (paramName in store) {
                if(paramName.indexOf("f"+$filterKey) > -1) {
                    unsetStoreItem(paramName, store[paramName]);
                }
            }
        }
/* ---------------------------------------------------------------------------------------- */

        getParams = function() {
           if(ajaxMode || sessionMode) {
               return getSessionParams();
           }
           else {
               return getGetParams();
           }
        },

        getGetParams = function () {

           var search = window.location.search;

           if(!search) {
             return {};
           }
           else {
              return search
                  .replace('?','')
                  .split('&')
                  .reduce(
                      function(p,e) {
                          var a = e.split('=');
                          p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                          return p;
                      },
                      {}
                  );
                }
           },

        getSessionParams = function() {
            if(!sessionStorage.getItem(oipCatalogFilterName)) {
                return {};
            }

            var
                sesStorage = sessionStorage.getItem(oipCatalogFilterName);

            return sesStorage
                .split('&')
                .reduce(
                    function(p,e) {
                        var a = e.split('=');
                        p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                        return p;
                    },
                    {}
                );
        },

        mergeParams = function (store, getParams, filterId) {

            var newParams = {};

            if(!objectIsEmpty(getParams)) {
                for (var name in  getParams) {
                    if(getParams.hasOwnProperty(name)) {

                        if(name in store) {
                            newParams[name] = store[name];
                        }
                        // параметр есть в урле, но нет в сторе
                        else {

                            var paramMap = getParamMap(name);

                            // параметр не относится к фильтрам вообще - оставить
                            if(objectIsEmpty(paramMap)) {
                                newParams[name] = getParams[name];
                            }
                            // параметр не относится к текущему фильтру - оставить
                            else if(paramMap.filterId != filterId) {
                                newParams[name] = getParams[name];
                            }

                            // иначе параметр из текущего фильтра, но его нет в сторе - он больше не акутален
                            // - выкинуть из урла
                        }
                    }
                }

            }

            // то, что есть в сторе, но нет в урле - записать
            for (var name in store) {
                if(store.hasOwnProperty(name)) {
                    newParams[name] = store[name];
                }
            }

            return newParams;
        },

        encodeGetParams = function (paramsObject) {

            var search = "";

            for (var propName in paramsObject) {
                if(search) {
                    search += "&"+propName+"="+paramsObject[propName];
                }
                else {
                    search = propName+"="+paramsObject[propName];
                }

            }

            return search;
        },
        getParamMap = function(paramName, paramValue = null) {

            var map = {};

            if(!paramName) {
                return map;
            }

            var arParamName =  paramName.split("_");

            if(arParamName[0].slice(0,1) !== "f") {
                return map;
            }

            var
                filterId = arParamName[0].slice(1),
                paramType = arParamName[1].slice(0,1),
                paramName = arParamName[1].slice(1).replace("-","_");

            map.filterId = filterId;
            map.paramType = paramType;
            map.paramName = paramName;

            if(paramValue) {
                map.paramValue = paramValue;
            }

            return map;
        }
        ;
/* -------------------------------------------------------------------------------------- */
     return {
        State: {
            isAjaxMode: isAjaxMode
        },
        Helpers: {
            Object: {
                isEmpty: objectIsEmpty
            },

            Array: {
                removeByValue: arrayRemoveByValue
            },

            List: {
                addEventListener:listAddEventListener,
                each: listEach
            }
        },

        Filter: {
            getCheckboxName: getCheckboxName,
            getCheckboxValue: getCheckboxValue,
            apply: applyFilter

        },
        Store: {
            init: initStore,
            getStore: getStore,
            getItem: getStoreItem,
            setItem: setStoreItem,
            unsetItem: unsetStoreItem,
            unsetFilter: unsetStoreFilter
        }
    };

}());