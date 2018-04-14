<?php if (!empty($diferenciais)):?>
<div class="diferenciais full-height" id="diferenciais">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <?php if (!$this->isPhone):?>
                <img src="<?=$this->url('/assets/site/images/diferenciais.png')?>" alt="Diferenciais"
                     class="img-responsive w-100 d-none d-lg-block"/>
                    <h1 class="section__title d-block d-lg-none">Diferenciais</h1>
                <?php else:?>
                    <h1 class="section__title">Diferenciais</h1>
                <?php endif?>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="ml-4">
                    <?php
                    foreach ($diferenciais as $d):
                        $itens = explode("\n", $d['itens']);
                        if (empty($itens)) {
                            continue;
                        }
                        $itens = array_filter(array_map('trim', $itens));
                    ?>
                    <p class="mb-0">
                        <strong><?=$d['nome']?></strong>
                        <ul class="diferenciais__list mb-4">
                            <?php foreach ($itens as $i):?>
                            <li>
                                <?=trim($i)?>
                            </li>
                            <?php endforeach?>
                        </ul>
                    </p>
                    <?php endforeach?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif?>