<?php
if (empty($model))
    return;

$modelHasError = $model->hasErrors();
/*@var $form Model_Form*/
?>
<?= $this->renderFlashes()?>
<div>
    <?php if (!empty($formLayout)):?>
        <?=$form->setAttr('class', 'form-stacked form-validation')->setAttr('id', 'admin-form')->open();?>
        <?php
        foreach ($formLayout as $row):
            if (is_string($row)) {
                echo $row;
                continue;
            }
        ?>
            <div class="row clearfix">
                <?php
                foreach ($row as $field_name=>$size):
                    $isField = !is_int($field_name);
                    $type     = 'field';

                    if (!$isField) {
                        $type              = 'custom_content';
                        $isCustomContent   = true;
                        $custom_content    = $size[0];
                        if (is_callable($custom_content)) {
                            $custom_content = call_user_func($custom_content, $form, $model);
                        }
                        $isSelfContained   = !isset($size[1]) || empty($size[1]);
                        $isRadioOrCheck    = false;


                        if (!$isSelfContained) {
                            $size = $size[1];
                        }
                    } else if ($isField && is_array($size)) {
                        $type              = 'custom_field';
                        $field             = $size[0];
                        $isRadioOrCheck    = $model->getField($field_name) instanceof \Mix\Model\Field\Boolean;

                        $isSelfContained = !isset($size[1]) || empty($size[1]);

                        if (!$isSelfContained) {
                            $size = $size[1];
                            if (!$isRadioOrCheck) {
                                $field->addClass('col-md-12');
                                $field->addClass('col-sm-12');
                            }
                        }
                    } else {
                        $isSelfContained   = empty($size);
                        $field             = $form->modelField($field_name);
                        $isRadioOrCheck    = $model->getField($field_name) instanceof \W5n\Model\Field\Boolean;
                        if (!$isSelfContained && !$isRadioOrCheck) {
                            $field->addClass('col-md-12');
                            $field->addClass('col-sm-12');
                        }
                    }
                ?>
                <?php if (!$isSelfContained):?>
                    <div class="col-md-<?php echo $size?><?php echo $model->hasError($field_name) ? ' has-error': ($modelHasError ? ' has-success' : '')?> col-sm-12<?=!$isRadioOrCheck ? ' form-group' : ''?>">
                <?php endif;?>
                        <?php if ($type == 'field' || $type == 'custom_field'):?>
                            <?php
                                if ($isRadioOrCheck) :
                                    $field->setAttr('id', 'field_'.$field_name);
                                    $field->removeClass('span' . $size);
                                    echo '<div class="input-group" style="margin:23px 0 5px 0">';
                                    echo '<span class="input-group-addon">';
                                    echo $field;
                                    echo '</span>';
                                    echo $form->modelLabel($field_name, 'field_'.$field_name)->addClass('form-control');
                                    echo $form->modelInfo($field_name);
                                    echo $form->modelError($field_name);
                                    echo '</div>';
                                 else:
                                    if (in_array($field->getName(), array('input', 'textarea', 'select')))  {
                                        if ($field->getName() != 'input' || ($field->getName() == 'input' &&
                                            !in_array($field->getAttr('type'), array('file', 'button', 'image', 'submit')))) {
                                            $field->addClass('form-control');
                                        }

                                    }
                            ?>
                                <?php
                                $label = $form->modelLabel($field_name, 'field_'.$field_name);
                                if (!empty($label)) {
                                    $label->appendText(':', false);
                                }
                                echo $label;
                                ?>
                                <div class="input">
                                    <?= $field->setAttr('id', 'field_'.$field_name);?>
                                </div>
                                <?= $form->modelInfo($field_name, '<small class="help-block">', '</small>');?>
                                <?= $form->modelError($field_name);?>
                            <?php endif;?>
                        <?php else:?>
                            <?php echo $custom_content?>
                        <?php endif;?>

                <?php if (!$isSelfContained):?>
                </div>
                <?php endif;?>


                <?php endforeach;?>
            </div>
        <?php endforeach;?>
    <?php else:?>
        <?= $form->open();?>
        <?php
        $fields     = $model->getFields();
        $formFields = array();
        foreach ($fields as $name => $f) {
            if ($f instanceof W_Field_Html)
                $formFields[] = $name;
        }
        foreach ($formFields as $f):
        ?>

            <div class="clearfix<?php echo $model->hasError($f) ? ' error': ($modelHasError ? ' success' : '')?>">
                <?php
                    $field = $form->modelField($f)->setAttr('id', 'field_'.$f);

                    if ($field instanceof W_Html_Form_InputCheckbox ||
                        $field instanceof W_Html_Form_InputRadio) {
                        echo '<div class="input">';
                        echo $form->modelLabel($f, 'field_'.$f)
                                ->append('?')
                                ->prepend(' ')
                                ->prepend($field)
                                ->attr('style', 'text-align:left;width:auto;');
                        echo $form->modelInfo($f);
                        echo $form->modelError($f);
                        echo '</div>';
                    } else {
                    ?>
                    <?php echo $form->modelLabel($f, 'field_'.$f)->append(':');?>
                        <div class="input">
                            <?php echo $field?>
                            <?php echo $form->modelInfo($f, '<span class="help-block">', '</span>')?>
                            <?php echo $form->modelError($f)?>
                        </div>
                    <?php
                    }
                    ?>
            </div>
        <?php endforeach?>
    <?php endif?>
    <div class="actions row-fluid" style="margin:20px 0">
        <?php if (!isset($backButton) || $backButton):?>
        <?php
        $queryParams = $request->query->all();
        $routeParams = $request->request->all();
        $routeParams['config'] = $configKey;

        $routeParams = array_filter($routeParams, function ($value) {
            return !is_array($value);
        });
        ?>
            <a href="<?php echo $this->routeUrl($listRoute, $routeParams).(empty($queryParams) ? '' : '?'.  http_build_query($queryParams))?>" class="btn-flat btn btn-mini btn-default hidden-print"><i class="fa fa-chevron-circle-left"></i> Voltar</a>
        <?php endif?>
        <?php
        $buttonLabel = !empty($submitLabel) ? $submitLabel : 'Salvar ' . $config['singular'];
        ?>
        <button type="submit" class="btn-flat btn btn-large btn-primary hidden-print">
                    <i class="fa fa-check"></i>
                    <?=$buttonLabel?>
        </button>
    </div>
    <?= $form->close();?>
</div>
