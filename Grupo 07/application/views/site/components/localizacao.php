<?php
use Configuracoes\Model\Configuracao as C;
?>
<section class="localizacao" id="localizacao">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <?php if (!$this->isPhone):?>
                    <img src="<?=$this->url('/assets/site/images/localizacao.png')?>" alt="Localização"
                         class="img-responsive w-100 img-title d-none d-lg-block"/>
                    <h1 class="section__title d-block d-lg-none">Localização</h1>
                <?php else:?>
                    <h1 class="section__title">Localização</h1>
                <?php endif?>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="ml-4">
                    <img src="<?=$this->url('/assets/site/images/mapa.png')?>" alt="" class="img-responsive w-100" />
                    <p class="text-center lead">
                        Prolongamento da Rua Prof. Olavo Lacerda Montenegro, 1562, Parnamirim/RN.
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center mt-5 mb-3">
                        <img src="<?=$this->url('/assets/site/images/realizacao.png')?>" alt=""
                             class="<?=$this->isMobile ? 'w-100' : 'img-responsive'?>" />
                    </div>
                    <div class="col-md-12 mb-5 ml-4">
                        <p class="disclaimer">
                            <?=C::get('texto_footer')?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>