<?php
use Admin\Ui\UiManager as Ui;
use W5n\Routing\Router;

if (!isset($this['admin.ui_manager'])) {
    return;
}

$ui = $this['admin.ui_manager'];
