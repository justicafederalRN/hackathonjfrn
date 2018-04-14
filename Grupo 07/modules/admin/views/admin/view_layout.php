<?php if (!empty($layout) && !empty($form)):?>
    <?php 
    $rowIndex = 0;
    foreach ($layout as $row):?>
        <div class="row clearfix row-<?=++$rowIndex?>">
            <?php                 
            foreach ($row as $field_name=>$size):               
                $isField = !is_int($field_name);              
                    $type     = 'field';
                    $isCustomContent = false;
                    
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
                        $isRadioOrCheck    = $model->getField($field_name) instanceof \Mix\Model\Field\Boolean;
                        if (!$isSelfContained && !$isRadioOrCheck) {
                            $field->addClass('col-md-12');
                            $field->addClass('col-sm-12');
                        }
                    }     
                    if (!$isCustomContent) {
                        $value = $form->modelDisplayValue($field_name);                    
                    }
                
            ?>
            <?php if (!$isSelfContained):?>
                <div class="col-md-<?php echo $size?> col-sm-12">
            <?php endif;?>
                    <?php if ($type == 'field' || $type == 'customField'):?>

                        <strong><?php echo $form->modelLabel($field_name, 'field_'.$field_name);?></strong>
                        <div class="input">
                            <div class="form-control-static">
                                <?php


                                if (empty($value)) {
                                    echo '<small class="text-muted" style="font-style:italic">&lt;Não informado&gt;</small>';
                                } else {
                                    echo $value;
                                }
                                ?>
                            </div>                                
                        </div>
                    <?php elseif ($isCustomContent):?>
                        <?=$custom_content?>
                    <?php endif;?>

            <?php if (!$isSelfContained):?>
            </div>
            <?php endif;?>


            <?php endforeach;?>
        </div>
    <?php endforeach;?>
<?php endif?>