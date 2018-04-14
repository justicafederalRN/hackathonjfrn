<?php if (!empty($imagens)):?>
<div class="carousel__container">
    <div id="carousel-prev">
        <a href="javascript:void(0);">
            <img src="<?=$this->url('/assets/site/images/prev-button.png')?>" alt="&laquo;" />
        </a>
    </div>
    <div id="carousel-next">
        <a href="javascript:void(0);">
            <img src="<?=$this->url('/assets/site/images/next-button.png')?>" alt="&raquo;" />
        </a>
    </div>
    <div class="carousel" id="imagens">
        <?php foreach($imagens as $i):?>
        <div class="carousel__item">
            <?php if ($this->isMobile):?>
                <img data-lazy="<?=$this->url('/assets/uploads/images/vitrines/md/' . $i['imagem'])?>" class="w-100" alt="" />
            <?php else:?>
                <img data-lazy="<?=$this->url('/assets/uploads/images/vitrines/' . $i['imagem'])?>" class="w-100" alt="" />
            <?php endif?>
        </div>
        <?php endforeach?>
    </div>
</div>
<?php endif?>