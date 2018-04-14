<?php
namespace Application;

use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Models\FeatureBasedNB;
use NlpTools\Documents\TrainingSet;
use NlpTools\Documents\TokensDocument;
use NlpTools\FeatureFactories\DataAsFeatures;
use NlpTools\Classifiers\MultinomialNBClassifier;
use \NlpTools\Similarity\CosineSimilarity;

class NLPService
{
    private $connection = null;

    public function __construct($conn)
    {
        $this->connection = $conn;
    }

    public function predictTutela($process_id, &$probability = null)
    {
        $query = $this->connection->createQueryBuilder();
        $result_set = $query->select('p.id', 'p.tutela', 'a.nome as assunto')
            ->from('processos', 'p')
            ->join('p', 'assuntos', 'a', 'a.id=p.assunto_id')
            ->where('p.tutela is not null')
            ->execute();

        $total_rows = $result_set->rowCount();

        $tset = new TrainingSet(); // will hold the training documents
        $tok  = new WhitespaceTokenizer(); // will split into tokens
        $ff   = new DataAsFeatures(); // see features in documentation

        $current_line_idx = 0;
        $to_test          = [];

        while (($line = $result_set->fetch()) !== false) {
            $current_line_idx++;

            $assunto = $line['assunto'];
            $tutela  = $line['tutela'] ? 'Sim' : 'Não';

            if ($line['id'] == $process_id) {
                $to_test = $line;
                continue;
            }

            $tset->addDocument(
                $tutela,
                new TokensDocument(
                    $tok->tokenize($assunto)
                )
            );
        }

        if (empty($to_test)) {
            return null;
        }

        $modelClassifier = new FeatureBasedNB($ff, $tset);
        $modelClassifier->train($ff, $tset);

        $cls     = new MultinomialNBClassifier($ff, $modelClassifier);
        $correct = 0;

        $document = new TokensDocument(
            $tok->tokenize($to_test['assunto'])
        );
        $result = $cls->classify(
            ['Sim', 'Não'],
            $document
        );
        $probability = $modelClassifier->getPrior($result);
        return $result;
    }

    public function calculateSimilarity($text1, $text2)
    {
        $cos = new CosineSimilarity();
        $tok = new WhitespaceTokenizer();
        
        $set1 = $tok->tokenize($text1);
        $set2 = $tok->tokenize($text2);

        return $cos->similarity($set1, $set2);
    }


    public function predictProcedencia(
        $process_id,
        &$probability = null,
        &$related_processes = [],
        $text_similarity_threshold = 0.45
    ) {
        $app  = \Application::getDefault();
        $repo = $app['processoRepository'];
        $processo = $repo->findById($process_id);

        if (empty($processo) || empty($processo['relatorio'])) {
            return null;
        }
        $query = $this->connection->createQueryBuilder();
        $result_set = $query->select('relatorio', 'procedente', 'numero_processo', 'p.id', 'procedente')
            ->from('processos', 'p')
            ->where('p.magistrado_id=:mid ')
            ->andWhere('p.relatorio is not null')
            ->andWhere('p.procedente != \'-\'')
            ->setParameter('mid', $processo['magistrado_id'])
            ->orderBy('random()')
            ->execute();

        $total_rows = $result_set->rowCount();

        $related_processes = [];

        $tset = new TrainingSet(); // will hold the training documents
        $tok  = new WhitespaceTokenizer(); // will split into tokens
        $ff   = new DataAsFeatures(); // see features in documentation

        $current_line_idx = 0;
        $test_set = [];

        $related_processes = [];

        while (($line = $result_set->fetch()) !== false) {
            $current_line_idx++;
            if ($line['id'] == $processo['id']) {
                continue;
            }
            $similarity = $this->calculateSimilarity(
                $processo['relatorio'],
                $line['relatorio']
            );

            if ($similarity < $text_similarity_threshold) {
                continue;
            }
            $related_processes[] = $line;

            $relatorio  = $line['relatorio'];
            $procedente = $line['procedente'];

            $document = new TokensDocument($tok->tokenize($relatorio));
            $tset->addDocument(
                $procedente,
                $document
            );
        }

        if (empty($related_processes)) {
            return null;
        }

        $modelClassifier = new FeatureBasedNB($ff, $tset);
        $modelClassifier->train($ff, $tset);

        $cls        = new MultinomialNBClassifier($ff, $modelClassifier);
        $document   = new TokensDocument($tok->tokenize($processo['relatorio']));
        $prediction = $cls->classify(
            ['I', 'P'],
            $document
        );

        $probability = $modelClassifier->getPrior($prediction);

        return $prediction;
    }

    public function decodeProcessNumber($processNumber)
    {
        list($serial, $year, $type, $court, $location) = explode('.', $processNumber);
        $uf   = substr($location, 0, 2);
        $city = substr($location, 2, 2);
        $cities = [
            '00' => 'Natal',
            '01' => 'Mossoró',
            '02' => 'Caicó',
            '03' => 'Assu',
            '04' => 'Pau dos Ferros',
            '05' => 'Ceará Mirim'
        ];

        return [
            'year' => $year,
            'type' => $type == 4 ? 'Justiça Federal' : null,
            'court' => $court == '05' ? 'Tribunal Regional Federal da 5ª Região' : null,
            'uf'    => $uf == '84' ? 'Rio Grande do Norte' : null,
            'city'  => isset($cities[$city]) ? $cities[$city] : null
        ];
    }


}
