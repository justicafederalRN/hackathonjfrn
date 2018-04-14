<?php
$total   = count($rowActions);
$current = 0;

foreach ($rowActions as $id => $action):
    $current++;
    $action = array_map(function ($param) use ($row) {
        if (\is_callable($param)) {
            return \call_user_func($param, $row, $this);
        }
        return $param;
    }, $action);
?>
<a href="<?=$action['url']?>"<?= $action['attrsStr']?> title="<?=$action['title']?>"><i class="fa fa-lg fa-<?=$action['icon']?>"></i></a>
<?php if ($current < $total):?>
&nbsp;&nbsp;
<?php endif?>
<?php endforeach; ?>
