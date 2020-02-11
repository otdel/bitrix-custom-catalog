<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
    <?if ($GLOBALS['VENDOR_SCRIPTS'] !== ''):?>
        <script src="/local/dist/<?=$GLOBALS['VENDOR_SCRIPTS'];?>"></script>
    <?endif;?>
    <?if ($GLOBALS['CUSTOM_SCRIPTS'] !== ''):?>
        <script src="/local/dist/<?=$GLOBALS['CUSTOM_SCRIPTS'];?>"></script>
    <?endif;?>
</body>

</html>
