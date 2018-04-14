<?php
if (empty($model))
    return;

$modelHasError = $model->hasErrors();
/*@var $form Model_Form*/
?>
<?php foreach ($formLayout as $row): ?>
    <div class="form-row clearfix">
        <?php
        foreach ($row as $field_name => $size):
            $isField = !is_int($field_name);
            $type    = 'field';

            if (!$isField) {
                $type            = 'custom_content';
                $isCustomContent = true;
                $custom_content  = $size[0];
                if (is_callable($custom_content)) {
                    $custom_content = call_user_func($custom_content, $form, $model);
                }
                $isSelfContained = !isset($size[1]) || empty($size[1]);
                $isRadioOrCheck  = false;


                if (!$isSelfContained) {
                    $size = $size[1];
                }
            } else if ($isField && is_array($size)) {
                $type           = 'custom_field';
                $field          = $size[0];
                $isRadioOrCheck = $model->getField($field_name) instanceof \Mix\Model\Field\Boolean;

                $isSelfContained = !isset($size[1]) || empty($size[1]);

                if (!$isSelfContained) {
                    $size = $size[1];
                    if (!$isRadioOrCheck) {
                        $field->addClass('col-md-12');
                        $field->addClass('col-sm-12');
                    }
                }
            } else {
                $isSelfContained = empty($size);
                $field           = $form->modelField($field_name);
                $isRadioOrCheck  = $model->getField($field_name) instanceof \Mix\Model\Field\Boolean;
                if (!$isSelfContained && !$isRadioOrCheck) {
                    $field->addClass('col-md-12');
                    $field->addClass('col-sm-12');
                }
            }
            ?>
            <?php if (!$isSelfContained): ?>
                <div class="col-md-<?php echo $size ?><?php echo $model->hasError($field_name) ? ' has-error' : ($modelHasError ? ' has-success' : '') ?> col-sm-12<?= !$isRadioOrCheck ? ' form-group' : '' ?>">
            <?php endif; ?>
                <?php if ($type == 'field' || $type == 'custom_field'): ?>
                    <?php
                    if ($isRadioOrCheck) :
                        $field->setAttr('id', 'field_'.$field_name);
                        $field->removeClass('span' . $size);
                        echo '<div class="input-group input-group-checkbox" style="margin:23px 0 5px 0">';
                        echo '<span class="input-group-addon">';
                        echo $field;
                        echo '</span>';
                        echo $form->modelLabel($field_name, 'field_'.$field_name)->addClass('form-control');
                        echo $form->modelInfo($field_name);
                        echo $form->modelError($field_name);
                        echo '</div>';
                    else:
                        if (in_array($field->getName(), array('input', 'textarea', 'select'))) {
                            if ($field->getName() != 'input' || ($field->getName() == 'input' &&
                                !in_array($field->getAttr('type'), array('file', 'button', 'image', 'submit')))) {
                                $field->addClass('form-control');
                            }
                        }

                        $isUploadable = $model->getField($field_name) instanceof \W5n\Model\Field\Uploadable;

                        if ($isUploadable) {
                            $field->addClass('uploadable');
                        }
                        ?>
                        <?php
                        $label = $form->modelLabel($field_name, 'field_' . $field_name);
                        if (!empty($label)) {
                            if ($isUploadable) {
                                $label->appendText(':', false);
                                $label->addClass('uploadable__label');
                            }
                        }
                        if ($isUploadable) {
                            echo $label;
                        } else {
                            $field->setAttr('placeholder', $model->getField($field_name)->getLabel());
                        }
                        ?>
                        <div class="input">
                            <?= $field->setAttr('id', 'field_' . $field_name); ?>
                        </div>
                        <?= $form->modelInfo($field_name, '<small class="help-block">', '</small>'); ?>
                        <?= $form->modelError($field_name); ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php echo $custom_content ?>
                <?php endif; ?>

                <?php if (!$isSelfContained): ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
