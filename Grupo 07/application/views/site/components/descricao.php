<?php
use Configuracoes\Model\Configuracao as C;
?>
<div class="descricao full-height" id="conceito">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img src="<?=$this->url('/assets/site/images/logo-white.png')?>" alt="" class="img-responsive w-100" />
            </div>
            <div class="col-md-9">
                <div class="ml-md-4 ml-sm-0">
                    <p>
                        <?=C::get('texto_conceito')?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
