<?php
/** @var $component \COipIblockElementListPage */
?>

<div class="uk-margin-medium uk-grid-large uk-flex-top uk-text-small uk-grid" uk-grid>


                <div class="uk-width-3-4@m">
                    <div class="uk-panel">
                        <div class="uk-flex-top" uk-grid>

                            <div class="uk-width-auto">
                                <?include_once (__DIR__."/brands.php")?>
                            </div>

                            <div class="uk-width-expand">
                                <?include_once (__DIR__."/tags.php")?>
                            </div>

                        </div>
                    </div>
                </div>


                <?if($component->isParam("SHOW_SORT")):?>
                    <div class="uk-width-1-4@m">

                        <div class="uk-flex uk-flex-right@m">
                            <?include_once (__DIR__."/sort.php")?>
                        </div>

                    </div>
                <?endif?>
</div>
