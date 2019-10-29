<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Config\Option;
use Arrilot\BitrixMigrations\Logger;

class SetMainModuleMinificationsCssSettings20191028142705947913 extends BitrixMigration
{
    public function up()
    {
        try {
            Option::set("main","optimize_css_files","Y");
            Option::set("main","optimize_js_files","Y");
            Option::set("main","use_minified_assets","Y");
            Option::set("main","move_js_to_body","Y");
            Option::set("main","compres_css_js_files","Y");
        }
        catch(\Bitrix\Main\ArgumentOutOfRangeException $e) {
          Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }

    }
    public function down()
    {
        try {
            Option::set("main", "optimize_css_files", "Y");
            Option::set("main", "optimize_js_files", "Y");
            Option::set("main", "use_minified_assets", "Y");
            Option::set("main", "move_js_to_body", "N");
            Option::set("main", "compres_css_js_files", "N");
        }
        catch(\Bitrix\Main\ArgumentOutOfRangeException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
