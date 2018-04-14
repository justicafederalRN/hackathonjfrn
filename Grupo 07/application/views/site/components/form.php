<div class="row align-items-center h-100">
    <div class="col-auto callout col-12 col-lg-auto">
        <p class="m-0 d-none d-lg-block">
            Receba<br /> o folder<br /> digital
        </p>
        <p class="m-0 callout d-block d-lg-none">
            <a href="javascript:void(0);" class="callout__action">
                Receba o folder digital
            </a>
        </p>
    </div>
    <div class="col">
        <p class="form-bar__message">

        </p>
        <?= $form->addClass('validate')->open() ?>
        <div class="row my-0 my-md-2">
            <div class="col-lg-6 col-md-12">
                <?= $form->modelField('nome')->addClass('form-control')->setAttr('placeholder', 'Nome') ?>
            </div>
            <div class="col-lg-6 col-md-12 my-3 my-md-0">
                <?= $form->modelField('telefone')->addClass('form-control')->setAttr('placeholder', 'Telefone') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <?= $form->modelField('email')->addClass('form-control')->setAttr('placeholder', 'E-mail') ?>
            </div>
            <div class="col-lg-6 col-md-12 mt-3 mt-md-0">
                <button type="submit">Enviar</button>
            </div>
        </div>
        <?= $form->close() ?>

    </div>
</div>
