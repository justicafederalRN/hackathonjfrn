<?php
$fields = [
    'classe_processual' => 'Classe Processual',
    'vara'              => 'Vara',
    'assunto'           => 'Assunto',
    'parte_autora'      => 'Parte Autora',
    'parte_autora'      => 'Parte Autora',
    'parte_re'          => 'Parte Ré',
    'advogado'          => 'Advogado',
    'magistrado'        => 'Magistrado',
];

$processNumberFields = [
    'year'   => 'Ano',
    'type'   => 'Órgão',
    'court'  => 'Tribunal',
    'uf'   => 'Estado',
    'city'   => 'Cidade'
];
$status = 'aberto';
$statusLabel = 'warning';

if ($processo['procedente'] == 'P') {
    $status      = 'procedente';
    $statusLabel = 'success';
} elseif ($processo['procedente'] == 'I'){
    $status      = 'improcedente';
    $statusLabel = 'danger';
}
?>
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-body">
                <h3 style="margin-top:0">Informações</h3>
                <table style="width:100%">
                    <?php foreach ($fields as $f => $label):?>
                    <tr>
                        <th style="padding:5px; text-align: right;"><?=$label?>:</th>
                        <td><?=$processo[$f]?></td>
                    </tr>
                    <?php endforeach?>
                    <?php if (!empty($processo['tutela']) && strlen($processo['tutela']) == 0):?>
                    <tr>
                        <th style="padding:5px; text-align: right">Solicitou Tutela?</th>
                        <td><?=$processo['tutela'] ? '<span class="label label-sucess">Sim</label>' : '<span class="label label-danger">Não</span>'?></td>
                    </tr>
                    <?php endif?>
                    <tr>
                        <th style="padding:5px; text-align: right">Situação:</th>
                        <td>
                            <?=sprintf(
                                '<span style="font-size:14px" class="label text-large label-lg label-%s">%s</span>',
                                $statusLabel,
                                ucfirst($status)
                            )?>
                        </td>
                    </tr>
                    <?php 
                    foreach ($processNumberFields as $field => $label):
                        if (empty($processNumberInfo[$field])) {
                            continue;
                        }
                    ?>
                        <tr>
                            <th style="padding:5px; text-align: right;"><?=$label?>:</th>
                            <td><?=$processNumberInfo[$field]?></td>
                        </tr>
                    <?php endforeach?>
                    <tr>
                        <th style="padding:5px;" colspan="2">Prévia do Processo:</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="pdf-object" id="pdf" data-src="<?=$this->url('/assets/processo_exemplo.pdf')?>">

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td style="padding:20px 0;">
                            <a href="<?=$this->url('/assets/processo_exemplo.pdf')?>" 
                                class="bnt btn-primary btn-lg" target="_blank">
                                <i class="fa fa-download"></i>
                                Baixar Processo
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="box">
            <div class="box-body">
                <?php if ($processo['procedente'] == '-'):?>
                    <h3 style="margin-top:0;margin-bottom:15px">Auxílio à Tomada de Decisão</h3>
                    <table>
                        <tr>
                            <th style="padding-bottom:5px;vertical-align:top">Predição de concessão de Tutela: &nbsp;</th>
                            <td>
                                <?php
                                $probSim = $tutela == 'Sim' ? $tutelaProbability : 1 - $tutelaProbability;
                                $probNao = 1 - $probSim;
                                ?>
                                Sim (<?= number_format($probSim * 100, 2, ',', '.')?>%)<br />
                                Não (<?= number_format($probNao * 100, 2, ',', '.')?>%)<br />
                            </td>
                        </tr>
                    </table>
                    <?php if (!empty($procedente)):?>

                    <table style="margin-top:20px">
                        <tr>
                            <th style="vertical-align:top">Predição de procedência:&nbsp;</th>
                            <td style="vertical-align:top">
                                <?php
                                $probP = $procedente == 'P' ? $procedenteProbability : 1 - $procedenteProbability;
                                $probI = 1 - $probP;
                                ?>
                                Procedente (<?=number_format($probP * 100, 2, ',', '.')?>%) <br />
                                Improcedente (<?=number_format($probI * 100, 2, ',', '.')?>%)
                            </td>
                        </tr>
                    </table>
                    <hr />
                    <?php else: ?>
                        <div class="alert alert-info">Não há dados suficientes para predizer a procedência.</div>
                    <?php endif ?>
                <?php endif?>
                <?php if (!empty($relatedProcesses)):?>
                    <?php if ($processo['procedente'] == '-'):?>
                        <h4 style="margin-top: 30px;margin-bottom:15px">Processos Similares</h4>
                    <?php else:?>
                        <h3 style="margin-top:0;margin-bottom:15px">Processos Similares</h3>
                    <?php endif?>
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th>Processo</th>
                            <th>Procedência</th>
                        </tr>
                        <?php foreach ($relatedProcesses as $p):?>
                            <tr>
                                <td>
                                    <a 
                                        href="<?=$this->routeUrl('admin.processos.detail', ['id' => $p['id']])?>"
                                        target="_blank">
                                        <?=$p['numero_processo']?>
                                    </a>
                                </td>
                                <td>
                                    <?= $p['procedente'] == 'P' ? 'Procedente' : 'Improcedente'?>
                                </td>
                            </tr>
                        <?php endforeach?>
                    </table>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
