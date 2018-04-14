<div class="hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right hero__slogan">
                    <img src="<?=$this->url('/assets/site/images/slogan.png')?>"
                         alt="O melhor de todos os condomínios em um só." />
                </div>
                <h1 class="m-0">
                    <a href="#">
                        <img src="<?=$this->url('/assets/site/images/logo.png')?>" alt="YBY Natureza" />
                    </a>
                </h1>
                <nav>
                    <ul class="main-menu">
                        <li class="main-menu__item">
                            <a href="#conceito">
                                Conceito
                            </a>
                        </li>
                        <?php if (!empty($imagens)):?>
                        <li class="main-menu__item">
                            <a href="#imagens">
                                Imagens
                            </a>
                        </li>
                        <?php endif?>
                        <?php if (!empty($diferenciais)):?>
                        <li class="main-menu__item">
                            <a href="#diferenciais">
                                Diferenciais
                            </a>
                        </li>
                        <?php endif?>
                        <?php if (!empty($news)):?>
                        <li class="main-menu__item">
                            <a href="#news">
                                News
                            </a>
                        </li>
                        <?php endif?>
                        <li class="main-menu__item">
                            <a href="#localizacao">
                                Localização
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
