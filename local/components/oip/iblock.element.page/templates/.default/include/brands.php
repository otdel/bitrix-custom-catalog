<?php
?>

<div class="uk-inline uk-active"> <!-- .uk-active, если что-то выбрано-->
    <button class="uk-button uk-button-default uk-button-small uk-text-lowercase" type="button">
        <i class="uk-margin-small-right" uk-icon="bookmark"></i>
        15 брендов
        <!-- Без фильтра: выберите бренд,
            выбран один бренд: название,
            более одного: выбрано X брендов.
            Дополнительно тут нужны правила склонения, это проще устно -->
    </button>

    <div uk-dropdown="mode: click">
        <form>
            <ul class="uk-nav uk-dropdown-nav">
                <li class="uk-active">
                    <a href="#">
                        <label><input class="uk-checkbox" type="checkbox" checked>&nbsp;Aктивный бренд</label>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <label><input class="uk-checkbox" type="checkbox">&nbsp;Бренд 1</label>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <label><input class="uk-checkbox" type="checkbox">&nbsp;Бренд 2</label>
                    </a>
                </li>
                <li class="uk-nav-divider"></li>
                <li class="uk-text-center">
                    <a class="uk-button uk-button-link uk-button-small" href="#">Выбрать все</a>
                </li>

            </ul>
        </form>
    </div>
    <i class="uk-icon-button uk-margin-small-left" uk-icon="close"></i> <!-- Только если выбраны бренды-->
</div>
