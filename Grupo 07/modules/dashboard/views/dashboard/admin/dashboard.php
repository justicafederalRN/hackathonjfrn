<?= $this->renderFlashes()?>
<h2>
    <?=empty($title) ? 'Títulos de ' . \Helpers\ViewHelper::getMonthName($mes) . ' de '. $ano : $title?>
    <?php 
    if (!$isPrint):
        $queryParams = $this->request->query->all();
        $queryParams['print'] = 1;
    ?>
        <a href="?<?=http_build_query($queryParams)?>" class="btn btn-sm pull-right btn-inverse" target="_blank">
            <i class="fa fa-print"></i>
            Imprimir
        </a>
    <?php endif?>
</h2>
<?php
$currentDay     = null;
$count          = 0;
$today          = strtotime(date('Y-m-d'));
$isCurrentMonth = $mes == date('n', $today) && $ano == date('Y', $today);
$totalTitles    = count($titulos);

$prevMonth = $nextMonth = [];

if ($mes == 1) {
    $prevMonth = [
        'a' => $ano - 1,
        'm' => 12
    ];
} else {
    $prevMonth = [
        'a' => $ano,
        'm' => $mes - 1
    ];
}

if ($mes == 12) {
    $nextMonth = [
        'a' => $ano + 1,
        'm' => 1
    ];
} else {
    $nextMonth = [
        'a' => $ano,
        'm' => $mes + 1
    ];
}
?>

<?php if ($totalTitles > 0):?>
    <strong>Quantidade de títulos:</strong> <?=$totalTitles?>
<?php endif?>

<?php if (empty($titulos)):?>
    <div class="alert alert-warning">
        Nenhum título encontrado.
    </div>
<?php endif?>

<?php if (!$isToday && !$isPrint):?>
    <div style="margin: 20px 0">
        <a class="btn pull-left btn-primary btn-sm" href="<?=$this->request->getPathInfo()?>?<?= \http_build_query($prevMonth)?>">
            &laquo; Mês anterior
        </a>
        <a class="btn pull-right btn-primary btn-sm" href="<?=$this->request->getPathInfo()?>?<?= \http_build_query($nextMonth)?>">
            Próximo mês &raquo;
        </a>
    </div>
    <div class="clearfix"></div>
<?php endif?>


<?php
$count       = [];
$sum         = [];
$statuses    = [];
$queryParams = $request->query->all();
foreach ($titulos as $t):
    $titulo  = new \Importacao\Model\Titulo();
    $titulo->populateFromArray($t, true, \Importacao\Model\Titulo::OP_DB_POPULATE);
    $status  = \Importacao\Model\Titulo::getStatus($t, $feriados);
    $time    = strtotime($t['data_vencimento']);
    $thisDay = date('j', strtotime($t['data_vencimento']));
    if (!isset($count[$status])) {
        $count[$status] = 0;
        $sum[$status]   = 0;
    }
    $column = in_array(
        $status,
        [\Importacao\Model\Titulo::STATUS_ATRASADO, \Importacao\Model\Titulo::STATUS_PENDENTE]
    ) ? 'valor' : 'valor_pago';

    $sum[$status] += floatval($t[$column]);
    ++$count[$status];
    if (array_search($status, $statuses) === false) {
        $statuses[] = $status;
    }
    $params         = $queryParams;
    $params['id']   = md5($t['id']);
    $params['type'] = $request->attributes->get('type', null);
    
?>
    <?php if ($thisDay != $currentDay):?>
        <?php if ($currentDay != null) echo '</tbody></table></div>'?>
    <div style="padding:20px 0px;" id="day-<?=$t['data_vencimento']?>">
        <table class="table table-condensed table-striped table-bordered">
            <thead style="background:#e5e5e5">
                <tr>
                    <th colspan="9" style="font-size:20px;text-align: center;background:#ccc">
                        <?=date('d/m/Y', strtotime($t['data_vencimento']))?>
                    </th>
                </tr>
                <tr>
                    <th>Título</th>
                    <th>Responsável</th>
                    <th style="text-align: center">Data de Vencimento</th>
                    <th style="text-align: center">Data de Pagamento</th>
                    <th style="text-align: right">Valor</th>
                    <th style="text-align: right">Valor Pago</th>
                    <th style="text-align: center">Situação</th>
                    <?php if (!isset($_GET['print'])):?>
                        <th style="text-align: center" title="Recebeu e-mail?"><i class="fa fa-envelope"></i></th>
                        <th style="text-align: center" title="Recebeu SMS?"><i class="fa fa-mobile"></i></th>
                    <?php endif?>
                </tr>
            </thead>
            <tbody style="background:#f0f0f0">
    <?php endif?>
    <tr id="titulo-<?=$t['id']?>">
        <td style="text-align:right"><?=$t['numero_documento']?></td>
        <td>
            <a href="#" data-contact-card="<?=$t['responsavel_id']?>">
                <?=$t['nome']?>
                <?php if (!empty($t['aluno'])): ?>
                    <i class="fa fa-user"></i>
                <?php endif ?>
            </a>
        </td>
        <td style="text-align: center"><?=date('d/m/Y', strtotime($t['data_vencimento']))?></td>
        <td style="text-align: center"><?=empty($t['data_pagamento']) ? '&mdash;' : date('d/m/Y', strtotime($t['data_pagamento']))?></td>
        <td style="text-align:right">R$ <?=number_format($t['valor'], 2, ',', '.')?></td>
        <td style="text-align:right">R$ <?=number_format((empty($t['valor_pago']) ? 0 : $t['valor_pago']), 2, ',', '.')?></td>
        <td style="text-align: center">
            <div <?= \Importacao\Model\Titulo::getStatusHtmlAttributes($t, $feriados)?>>
                <?= \Importacao\Model\Titulo::getStatusLabel($t, $feriados)?>
            </div>
        </td>

        <?php if (!isset($_GET['print'])):?>
        <td style="text-align:center">
            <?php if ($status == \Importacao\Model\Titulo::STATUS_PENDENTE && !empty($t['responsavel_email'])): ?>
                <a href="<?=$this->routeUrl('admin.titulos.enviar_email')?>?<?=http_build_query($params)?>" 
                    data-confirm="Deseja enviar o e-mail de cobrança para este cliente?" data-confirm-type="warning">
            <?php endif ?>
                    <i class="fa fa-<?=$t['email_enviado'] ? 'check' : 'remove'?>" style="color: <?=$t['email_enviado'] ? '#090' : '#c00'?>"></i>
            <?php if ($status == \Importacao\Model\Titulo::STATUS_PENDENTE && !empty($t['responsavel_email'])): ?>
                </a>
            <?php endif ?>
        </td>
        <td style="text-align:center">
            <?php if ($status == \Importacao\Model\Titulo::STATUS_PENDENTE && !empty($t['responsavel_celular'])): ?>
                <a href="<?=$this->routeUrl('admin.titulos.enviar_sms')?>?<?=http_build_query($params)?>" 
                    data-confirm="Deseja enviar o sms de cobrança para este cliente?" data-confirm-type="warning">
            <?php endif ?>
                    <i class="fa fa-<?=$t['sms_enviado'] ? 'check' : 'remove'?>"  style="color: <?=$t['sms_enviado'] ? '#090' : '#c00'?>"></i>
            <?php if ($status == \Importacao\Model\Titulo::STATUS_PENDENTE && !empty($t['responsavel_celular'])): ?>
                </a>
            <?php endif ?>
        </td>
        <?php endif?>
    </tr>
<?php
$currentDay = $thisDay;
endforeach;
?>
        </tbody>
    </table>
    <?php if (!empty($count)): ?>
        <h2>Resumo</h2>
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <tr>
                    <th>Situação</th>
                    <th style="text-align:right">Quantidade</th>
                    <th style="text-align: right">Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statuses as $status):?>
                <tr>
                    <td><?=\Importacao\Model\Titulo::getStatusLabel($status)?></td>
                    <td style="text-align:right"><?=$count[$status]?></td>
                    <td style="text-align:right">R$ <?=number_format($sum[$status], 2, ',', '.')?></td>
                </tr>
                <?php endforeach?>
            </tbody>
        </table>
    <?php endif ?>
</div>

<?php if ($isPrint):?>
<script type="text/javascript">
    window.print();
</script>
<?php endif?>
