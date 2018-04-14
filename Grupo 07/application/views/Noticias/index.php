<?php
$fields = [
    'titulo', 'texto', 'fonte', 'fonte_link', 'ativa'
];
?>
<?=$this->renderFlashes()?>
<?=$form->open()?>
<?php foreach ($fields as $f):?>
    <div>
        <?=$form->modelLabel($f)?>
        <div>
            <?=$form->modelField($f)?>
        </div>
        <?=$form->modelError($f)?>
    </div>
<?php endforeach?>
<button type="submit">Salvar</button>
<?=$form->close()?>