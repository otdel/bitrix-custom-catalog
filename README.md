# Кастомные решения Отдела Интернет-проектов

## Развертка проекта

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