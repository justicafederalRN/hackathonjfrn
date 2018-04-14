<?php
namespace Processos\Model;

use W5n\Model\Model;
use Doctrine\DBAL\Query\QueryBuilder;

class Peticao extends Model
{
    protected $table = 'processos';

    public static function modifyFindQueryBuilder(QueryBuilder $query)
    {
        $query->leftJoin(
            'processos',
            'interessados',
            'pa',
            'pa.id=processos.parte_autora_id'
        );

        $query->leftJoin(
            'processos',
            'interessados',
            'pr',
            'pr.id=processos.parte_re_id'
        );

        $query->leftJoin(
            'processos',
            'advogados',
            'a',
            'a.id=processos.advogado_id'
        );

        $query->leftJoin(
            'processos',
            'magistrados',
            'm',
            'm.id=processos.magistrado_id'
        );

        $query->leftJoin(
            'processos',
            'classes_processuais',
            'cp',
            'cp.id=processos.classe_processual_id'
        );

        $query->leftJoin(
            'processos',
            'assuntos',
            'ass',
            'ass.id=processos.assunto_id'
        );

        $query->leftJoin(
            'processos',
            'varas',
            'v',
            'v.id=processos.vara_id'
        );
    }
}
