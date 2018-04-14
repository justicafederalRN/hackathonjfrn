<?php
$search        = isset($config['search']) ? $config['search'] : null;
$hasSearchView = !empty($searchView);
?>
<?php
if (!empty($search)):
    if (isset($searchView)) {
        $hadLayout = $this->hasLayout();
        if ($hadLayout) {
            $this->disableLayout();
        }

        echo $this->render($searchView, $kohana_view_data);

        if ($hadLayout) {
            $this->enableLayout();
        }
        return;
    }
    $hasAjax = false;
?>
<h4>Filtro</h4>
<form class="form-inline form-validation" id="admin-search-form" style="margin-bottom: 20px;line-height:40px" method="get" action="<?= $this->request->getUri()?>">

    <?php
    $values = array();
    foreach ($search as $id => $info):
        $attrs = '';
        $type  = isset($info['type']) ? $info['type'] : 'text';
        $value = isset($_GET['search'][$id]) ? $_GET['search'][$id] : '';
        $values[$id] = $value;
        $isRange = isset($info['range']) && $info['range'];
        $field   = $searchModel->getField($id);
        $size    = isset($info['options']['size']) ? $info['options']['size'] : null;
    ?>
        <?php if ($type != 'boolean'):?>
        <div class="form-group<?=$field->hasError() ? ' has-error has-feedback' : ''?>">
            <?php
            $optionTypes = array(
                'options',
                'db_options',
                'ajax_options'
            );
            if (in_array($type, $optionTypes)):
                $options = array('' => '- ' . $info['label'] . ' -');

                if ($type == 'db_options') {
                    $info['options']['options'] = $searchModel->getField($id)->getResults();
                } else if ($type == 'ajax_options') {
                    $defaultOptions = array(
                        'empty_message'   => 'Nada foi encontrado.',
                        'loading_message' => 'Carregando...'
                    );
                    $fieldOptions = array_merge($defaultOptions, $info['options']);
                    $mandatoryKeys = array(
                        'depends', 'table', 'value', 'filter_field', 'filter_type'
                    );
                    foreach ($mandatoryKeys as $k) {
                        if (!isset($fieldOptions[$k])) {
                            throw new \LogicException(
                                sprintf(
                                    'Key "%s" must be present in ajax_option field type for field "%s".',
                                    $k, $id
                                )
                            );
                        }
                    }
                    $attrs = array(
                        'data-search-ajax-depends="' . $fieldOptions['depends'] . '"',
                        'data-search-ajax-config="' . $configKey . '"',
                        'data-search-ajax-field="' . $id . '"',
                        'data-search-ajax-loading-message="' . $fieldOptions['loading_message'] . '"',
                        'data-search-ajax-empty-message="' . $fieldOptions['empty_message'] . '"',
                        'data-search-ajax'
                    );
                    if (!empty($value)) {
                        $attrs['data-search-ajax-value'] = $value;
                    }
                    $info['options']['options'] = $searchModel->getField($id)->getOption('options', array());

                }
                if (!empty($info['options']['options'])) {
                    foreach ($info['options']['options'] as $v => $l) {
                        $options[$v] = $l;
                    }
                }
                if (!empty($attrs) && is_array($attrs)) {
                    $attrs = ' ' . implode(' ', $attrs);
                }
            ?>
            <select name="search[<?=$id?>]"
                <?php if ($field->hasError()):?>
                   data-rel="tooltip" data-trigger="hover" data-placement="top"
                   title="<?=  htmlentities($field->getError(), ENT_QUOTES, 'utf-8')?>"
                <?php endif?>
                id="search_<?=$id?>" class="form-control<?=$field->hasError() ? ' tooltips' : ''?>"<?= $attrs?> <?=!empty($size) ? 'style="width: ' . $size . 'px"' : ''?>>
                <?php foreach ($options as $v => $l):?>
                    <option<?=$value == $v && strlen($value) == strlen($v) ? ' selected="selected"': ''?> value="<?=$v?>"><?=$l?></option>
                <?php endforeach?>
            </select>
            <?php else:?>

            <input value="<?=$value?>"
                <?php if ($field->hasError()):?>
                   data-rel="tooltip" data-trigger="hover" data-placement="top"
                   title="<?=  htmlentities($field->getError(), ENT_QUOTES, 'utf-8')?>"
                <?php endif?>
                type="text" name="search[<?=$id?>]" id="search_<?=$id?>"  class="form-control input-<?=$type?><?=$field->hasError() ? ' tooltips' : ''?>"<?=!empty($size) ? 'style="width: ' . $size . 'px"' : ''?> placeholder="<?= $info['label']?>">
            <?php endif?>

        </div>
        <?php endif?>
    <?php endforeach?>
    <button type="submit" class="btn btn-primary">
        <i class="fa fa-search"></i>
    </button>
</form>
<?php endif?>
