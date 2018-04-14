<?php if (!empty($news)):?>
<section class="news full-height" id="news">
    <div class="container">

        <?php if (false):?>
        <div class="row">
            <div class="col-lg-12">
                <div class="<?=$this->isPhone ? 'text-center mb-3' : 'pull-right'?> social" id="news-social">
                    Siga
                    <a href="https://www.facebook.com/ybynatureza/" target="_blank" class="facebook">
                        <i class="fa fa-facebook-square fa-lg"></i></a>
                        <a href="https://www.instagram.com/ybynatureza/" target="_blank" class="instagram">
                        <i class="fa fa-instagram fa-lg"></i></a>
                    ybynatureza
                </div>
            </div>
        </div>
        <?php endif?>
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <?php if (!$this->isPhone):?>
                    <img src="<?=$this->url('/assets/site/images/news.png')?>" alt="News"
                         class="img-responsive w-100 img-title d-none d-lg-block"/>
                    <h1 class="section__title visible d-block d-lg-none">News</h1>
                <?php else:?>
                    <h1 class="section__title">News</h1>
                <?php endif?>
            </div>
            <div class="col-lg-8 col-md-12" id="noticia">
                <div class="ml-4">
                    <?php
                    foreach ($news as $idx => $n):
                        if ($idx != $currentNews) {
                            continue;
                        }
                    ?>
                    <article class="news__item">
                        <?php if (!empty($n['imagem_interna'])):?>
                            <img src="<?= $this->url('/assets/uploads/images/noticias/' . $n['imagem_interna'])?>"
                                 class="news__item__image w-100 img-responsive"/>
                         <?php endif?>
                        <h1 class="news__item__title pt-3"><?=$n['titulo']?></h1>
                        <div class="news__item__description">
                            <?=$n['texto']?>
                        </div>
                    </article>
                    <?php endforeach?>
                    <div class="row mt-5">
                        <?php if ($this->isPhone && count($news) > 1):?>
                        <div class="col-md-12">
                            <h2 class="mt-1 mb-4">Outras novidades</h2>
                        </div>
                        <?php endif?>
                        <?php foreach ($news as $idx => $n):
                            if ($idx == $currentNews){
                                continue;
                            }
                            $src = null;
                            if (!empty($n['imagem_chamada'])) {
                                $src = $this->url('/assets/uploads/images/noticias/' . $n['imagem_chamada']);
                            } else {
                                $src = $this->url('/assets/uploads/images/noticias/square/' . $n['imagem_interna']);
                            }
                        ?>
                            <div class="col-lg-3 col-md-12 news__item-small">
                                <a href="<?=$this->url('/')?>?n=<?=$idx?>#noticia">
                                    <img src="<?=$src?>" class="w-100 img-responsive"/>
                                    <h2><?=$n['titulo']?></h2>
                                </a>
                            </div>
                        <?php endforeach?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif?>

