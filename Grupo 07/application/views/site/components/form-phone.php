<p class="m-0 callout">
    <a href="javascript:void(0);" class="callout__action">
        Receba o folder digital
    </a>
</p>
<div class="">
    <p class="form-bar__message">

    </p>
    <?= $form->addClass('validate')->open() ?>
    <div>
        <div class="w-100">
            <div class="form-group">
                <?= $form->modelField('nome')->addClass('form-control')->setAttr('placeholder', 'Nome') ?>
            </div>
        </div>
        <div class="w-100">
            <div class="form-group">
                <?= $form->modelField('telefone')->addClass('form-control')->setAttr('placeholder', 'Telefone') ?>
            </div>
        </div>
        <div class="w-100">
            <div class="form-group">
                <?= $form->modelField('email')->addClass('form-control')->setAttr('placeholder', 'E-mail') ?>
            </div>
        </div>
        <div class="w-100">
            <button type="submit">Enviar</button>
        </div>
    </div>
    <?= $form->close() ?>
</div>
