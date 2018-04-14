<?php
$this->extend('layouts/site');
?>
<section class="interna">
    <header class="interna__header interna__header-<?=$this->renderBlock('bg', 'pizza')?>">
        <div class="container">
            <h1 class="interna__title">
                <?=$this->renderBlock('pageTitle')?>
            </h1>
        </div>
    </header>
    <div class="interna__content">
        <div class="container">
            <div class="section">
                <?php if ($this->hasBlock('title')):?>
                <div class="section__header">
                    <h2 class="section__title">
                        <?=$this->renderBlock('title')?>
                    </h2>
                    <?php if ($this->hasBlock('subtitle')):?>
                        <p class="section__subtitle mb-<?=isset($this->subtitleMarginBottom) ? $this->subtitleMarginBottom : 5?>">
                            <?=$this->renderBlock('subtitle')?>
                        </p>
                    <?php endif?>
                </div>
                <?php endif?>
                <div class="section__content">
                    <?=$this->getContent()?>
                </div>
            </div>
        </div>
    </div>
</section>