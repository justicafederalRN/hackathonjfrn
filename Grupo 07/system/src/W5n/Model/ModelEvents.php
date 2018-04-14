<?php
namespace W5n\Model;

class ModelEvents
{
    const BEFORE_SAVE = 'model.before_save';
    const AFTER_SAVE = 'model.after_save';
    const BEFORE_VALIDATE = 'model.before_validate';
    const AFTER_VALIDATE = 'model.after_validate';
    const BEFORE_INSERT = 'model.before_insert';
    const AFTER_INSERT = 'model.after_insert';
    const BEFORE_UPDATE = 'model.before_update';
    const AFTER_UPDATE = 'model.after_update';
    const BEFORE_DELETE = 'model.before_delete';
    const AFTER_DELETE = 'model.after_delete';

}
