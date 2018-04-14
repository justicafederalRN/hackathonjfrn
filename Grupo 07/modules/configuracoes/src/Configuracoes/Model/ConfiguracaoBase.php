<?php
namespace Configuracoes\Model;

class ConfiguracaoBase extends \W5n\Model\Model
{

    protected $table = 'configuracoes';
    protected static $configData = array();


    public function __construct(\W5n\Application $app = null)
    {
        parent::__construct($app);
        $this->populateFromArray(self::loadData(true));
    }

    protected static function loadData($force = false)
    {
        if (!$force && !empty(self::$configData)) {
            return self::$configData;
        }
        $query = self::getConnection()
            ->createQueryBuilder()->select('*')
            ->from('configuracoes');

        $data             = $query->execute()->fetchAll();
        self::$configData = array();
        foreach ($data as $d) {
            try {
                self::$configData[$d['chave']] = unserialize($d['valor']);
            } catch (\Exception $ex) {
                self::$configData[$d['chave']] = $d['valor'];
            }
        }
        return self::$configData;
    }

    public function save($validate = true, $forceInsert = false, $triggerBefore = true, $triggerAfter = true, $table = null)
    {
        $this->triggerBeforeSave(self::OP_INSERT);

        $values = $this->getFieldsToSave();

        if ($this->hasErrors()) {
            return false;
        }
        $configData = self::loadData(true);

        $thisData = $this->toArray();

        $toInsert = array();
        $toUpdate = array();

        foreach ($thisData as $key => $value) {
            if (!isset($values[$key])) {
                continue;
            }
            $value = $values[$key];
            $value = serialize($value);
            if (!isset($configData[$key])) {
                $toInsert[$key] = $value;
            } else {
                $toUpdate[$key] = $value;
            }
        }
        $db = self::getConnection();
        if (!empty($toInsert)) {
            $stmt = $db->prepare('INSERT INTO configuracoes(chave, valor) VALUES(?, ?)');
            foreach ($toInsert as $k => $v) {
                $stmt->execute(array($k, $v));
            }
        }
        if (!empty($toUpdate)) {
            $stmt = $db->prepare('UPDATE configuracoes SET valor=? WHERE chave=?');
            foreach ($toUpdate as $k => $v) {
                $stmt->execute(array($v, $k));
            }
        }
        $this->triggerAfterSave(true, self::OP_INSERT);
        return true;
    }

    public static function get($key, $default = null, $forceLoad = false)
    {
        self::loadData($forceLoad);
        return isset(self::$configData[$key]) ? self::$configData[$key] : $default;
    }

    public static function getAll($force = false)
    {
        return self::loadData($force);
    }

}
