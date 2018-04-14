<?php
$this->extend('site/interna');
?>
<?=$this->renderFlashes()?>
<?=$this->renderBlock('before_list')?>
<?php if (empty($items)):?>
    <p class="text-center lead mt-5">
        <?=$this->renderBlock('empty_message')?>
    </p>
<?php else:?>
    <div class="row">
        <?php foreach ($items as $item):?>
            <?=$this->renderPartial($itemView, ['item' => $item])?>
        <?php endforeach?>
    </div>
<?php endif?>
<?=$this->renderBlock('after_list')?>
<?=$this->renderBlock('before_pagination')?>
<?php if (isset($totalPages) && $totalPages > 1):?>
    <div class="pagination__container">
        <?= Helpers\ViewHelper::paginate($this, $page, $totalPages, $listRoute)?>
    </div>
<?php endif?>
<?=$this->renderBlock('after_pagination')?>
