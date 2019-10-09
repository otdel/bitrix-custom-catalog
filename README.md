# Кастомные решения Отдела Интернет-проектов

## Установка проекта

* клонировать проект
```
git clone git@bitbucket.org:otdel/oip_bitrix_custom.git
```
* накатить дамп базы и распаковать ядро (дамп - ``deploy/init.sql``)
* скопировать/переименовать/распаковать файлы конфигурации
```
deploy/htaccess.source > /.htaccess
deploy/settings.php.source > bitrix/.settings.php
deploy/php_interface.zip > bitrix/php_interface
```
* установить зависимости composer-а
* сгенерировать аннотации для ORM-сущностей кастомных модулей (пояснения см. ниже):
```
cd bitrix
php bitrix.php orm:annotate -c -m iblock,oip.iblock
```
* вручную зарегистрировать кастомный модуль в системе, выполнив файл ``development/reg.php``
или код ``RegisterModule("oip.iblock")`` в любом файле.

## Разработка на D7 c использованием ORM и аннотации классов

Основная информация по теме [здесь](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&CHAPTER_ID=05748&LESSON_PATH=3913.5062.5748).
Если кратко: чтобы делать на API битрикса с автозагрузкой классов и аннотациями в IDE, нужно [оформлять код в виде партнерского модуля](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=4809&LESSON_PATH=3913.5062.4809) и [выполнить
ряд манипуляций](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=11733&LESSON_PATH=3913.5062.5748.11733) для настройки удобной разработки.

В проекте уже выполнена необходимая настройка для генерирования аннотаций 
в файлах ``composer.json`` и ``bitrix/.settings.php``.
При первой разветке проекта достаточно выполнить раздел **Установка проекта.**


