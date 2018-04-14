<?php
namespace Application\Repository;

class ProcessoRepository 
{
    private $connection;

    public function __construct($conn)
    {
        $this->connection = $conn;
    }

    public function findById($processId)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select(
            'processos.*',
            'pa.nome as parte_autora',
            'pr.nome as parte_re',
            'a.nome as advogado',
            'm.nome as magistrado',
            'cp.nome as classe_processual',
            'ass.nome as assunto',
            'v.nome as vara'
        )->from('processos');

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

        return $query
            ->where('processos.id=:id')
            ->setParameter('id', $processId)
            ->execute()
            ->fetch();
    }
}
