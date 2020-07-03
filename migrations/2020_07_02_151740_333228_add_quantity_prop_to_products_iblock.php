<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddQuantityPropToProductsIblock20200702151740333228 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $ibId = $this->getIblockIdByCode("oip_products");

        $this->addIblockElementProperty([
            "NAME" => "Количество",
            "CODE" => "QUANTITY",
            "IBLOCK_ID" => $ibId,
            "PROPERTY_TYPE" => "N",
            "DEFAULT_VALUE" => 0
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $ibId = $this->getIblockIdByCode("oip_products");
        $this->deleteIblockElementPropertyByCode($ibId, "QUANTITY");
    }
}
