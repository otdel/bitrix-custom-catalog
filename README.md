# Кастомный каталог товаров Отдела Интернет-проектов

## Установка пакета

Все просто: 

1. ``composer require oip/custom:dev-master``
2. копировать каталоги ``/local/components/oip``, ``/local/templates/custom``, ``/local/js`` и ``/migrations`` в свой проект,
3. выполнить ``composer install`` для подгрузки зависимостей,
4. если до этого момента в проекте не использовался пакет [arrilot/bitrix-migrations](https://github.com/arrilot/bitrix-migrations#readme), выполнить инстукции по его инициализации.

## Frontend

### Настройка окружения:

Первичная настройка Webpack'a для Битрикса с инструкцией по автоматическому развертыванию: [bitrix-webpack](https://www.npmjs.com/package/bitrix-webpack).

Если webpack, vue (или react) уже настроены на проекте, и есть планы использовать шаблоны из папки `/bitrix`, то нужно выполнить пару простых шагов, иначе подключение вебпака к стандартным шаблонам можно пропустить. 

Нужно установить глобально npm-пакет bitrix-webpack, и выполнить две команды:

```(bash)
$ npm i -g bitrix-webpack
$ bitrix-webpack
$ [выбрать CSS (по умолчанию) и нажать Enter]
```

В любом случае следует установить зависимости с версиями, прописанными в `package-lock.json` (без обновления пакетов):

```(bash)
$ npm сi
```
## Подключение компонентов:

В примерах ниже указаны только обязательные параметры (те, без которых вылетит фатал), остальные настройки: 
пагинации, кеширования, шаблонов - опущены, т. к. создаются со значениями по умолчанию.

Для изменения поведения компонента нужно знать какой параметр с каким значением передавать.

### Комплексный каталог:

```
<?$APPLICATION->IncludeComponent("oip:iblock.element.complex","",[
    "IBLOCK_ID" => 29, // id инфоблока товароа
    "BASE_DIR" => "/catalog/", // базовая директория каталога
    "BRANDS_IBLOCK_ID" => 25,  // id инфоблока брендов для фильтрации по ним - если параметр опусить, фильтр не появится
    "TAGS_IBLOCK_ID" => 26,    // id инфоблока тегов для фильтрации по ним - если параметр опусить, фильтр не появится
])?>

```

### Страница списка товаров (список с допфильтрами по брендам, тегам и т.п.):

```
<?$APPLICATION->IncludeComponent("oip:iblock.element.page","",[
    "IBLOCK_ID" => 29,
    "BRANDS_IBLOCK_ID" => 25,  // id инфоблока брендов для фильтрации по ним - если параметр опусить, фильтр не появится
    "TAGS_IBLOCK_ID" => 26,    // id инфоблока тегов для фильтрации по ним - если параметр опусить, фильтр не появится
])?>
```

### Список товаров (с пагинацией):

```
<?$returnedData = $APPLICATION->IncludeComponent("oip:iblock.element.list","",[
    "IBLOCK_ID" => 29, // id инфоблока товароа
])?>

<?$pagination = $returnedData->getPagination()?>

<?if(!empty($pagination) && $pagination["PAGES"] > 1):?>
    <?$APPLICATION->IncludeComponent("oip:page.navigation","",[
        "NAV_ID" => $pagination["NAV_ID"],
        "PAGES" => $pagination["PAGES"],
        "PAGE" => $pagination["PAGE"],
    ])?>
<?endif?>

```

### Карточка товара:

```
<?$APPLICATION->IncludeComponent("oip:iblock.element.one","",[
    "IBLOCK_ID" => 29,
    "ELEMENT_CODE" => "test-product", // символьный код товара - обязателен только 1 из параметров, любой
    "ELEMENT_ID" => 375,              // id товара  - обязателен только 1 из параметров, любой
])?>
```

### Форма фильтра

Фильтр можно использовать только для одностраничных компонентов (список, страница списка), т.к. он шлет запросы с перезагрузкой текущей страницы.


```
<?$catalogFilter = $APPLICATION->IncludeComponent("oip:filter.form","",[
    "FILTER_ID" => 1, // id фильта, уникальное число, не должно повторяться на странице
                       // сейчас принимает только число 1..9, потом это поправлю
    "BRANDS_IBLOCK_ID" => 25 // id инфоблока брендов для подключения в форме фильтра по брендам
])?>
 
 ```
 после вызова формы, можно результат ее работы передать в список товаров:
 ```
 
<?$return = $APPLICATION->IncludeComponent("oip:iblock.element.list","",[
    "IBLOCK_ID" => 29, // id инфоблока товаров
    "FILTER" => $catalogFilter
])?>
```

### Дерево категорий

````
<?$APPLICATION->IncludeComponent("oip:iblock.section.list","",[
    "IBLOCK_ID" => 29,
    "BASE_SECTION" => "test-category", // код/id базовой категории - выведется дерево ее подкатегорий
                                       // можно опустить для вывода полного дерева
])?>
````

### Описание категории

```
<?$returnedSectionData = $APPLICATION->IncludeComponent("oip:iblock.section.list","",[
    "IBLOCK_ID" => 29,
    "BASE_SECTION" => "test-category",  // для вывода деталки обязателен
    "DEPTH" => 0, // глубина вложенности; для деталки обязателен 0
    "USER_FIELDS" => ["UF_*"],   // массив польз. полей, которые нужно вернуть; в данном случае все
])?>

<?// $returnedSectionData  - сюда пришли данные польз. полей + section_name ?>
```

### Страница корзины

```
<?$APPLICATION->IncludeComponent("oip:social.store.cart.page","",[])?>
```

