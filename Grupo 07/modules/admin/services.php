<?php

$this['admin.url_prefix'] = function() {
    return 'admin';
};

$this['admin.ui_manager'] = function() {
    return new \Admin\Ui\UiManager();
};