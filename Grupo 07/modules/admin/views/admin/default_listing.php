<div class="row-fluid">
    <?=$this->renderBlock('before_table')?>
    <?php if (!empty($searchModel)):?>
        <?php require_once 'search.php'?>
    <?php endif?>
    <?= $this->renderFlashes()?>
    <?php if ($hasData):?>
    <p class="data-count">
        Exibindo <strong><?=$firstRow?>-<?=$lastRow?></strong> de <strong><?=$totalRows?></strong>
    </p>
    <?= $table?>
    <?=$this->renderBlock('after_table')?>
    <?php echo $ui->renderPagination($this, $currentPage, $totalPages, $paginationRoute, ['config' => $configKey])?>
    <?=$this->renderBlock('after_pagination')?>
    <?php endif?>
</div>