<?php
namespace W5n;

/**
 * @author Waldson Patrício <waldsonpatricio@gmail.com>
 */
class Table
{

    private static $filters = [];

    protected $dataset;
    protected $headers             = array();
    protected $headersAttributes   = array();
    protected $columnsAttributes   = array();
    protected $columnFilters       = array();
    protected $hiddenColumns       = array();
    protected $cellsTemplates      = array();
    protected $virtualColumns      = array();
    protected $weights             = array();
    protected $tableAttributes     = array();
    protected $rowsAttributes      = array();
    protected $headerRowAttributes = array();
    protected $rowsCallback        = null;
    protected $sharedData          = array(); //valores que são os mesmos para todas as linhas
    protected $searchData        = array();

    /*
     * PH = placeholder
     */

    const PH_COLUMN        = '$column$';
    const PH_CURRENT_ROW   = '$currentRow$';
    const PH_ROW_COUNT     = '$rowCount$';
    const PH_VALUE         = '$value$';
    const PH_DATASET_VALUE = '{%s}';

    function __construct($dataset)
    {
        $this->dataset = $dataset;
    }

    function setFilter($columnId, $callback)
    {
        if (!is_callable($callback)) {
            if (!is_string($callback) || is_string($callback) && !array_key_exists($callback, self::$filters)) {
                throw new \W5n\Exception('Filter for column ' . $columnId . ' is not callable.');
            }
        }
        if (is_array($columnId)) {
            foreach ($columnId as $c) {
                $this->columnFilters[$c] = $callback;
            }
        } else {
            $this->columnFilters[$columnId] = $callback;
        }
    }

    function hasFilter($columnId)
    {
        return !empty($this->columnFilters[$columnId]);
    }

    function setShared($key, $data)
    {
        $this->sharedData[$key] = $data;
    }

    function getShared($key, $default = NULL)
    {
        return $this->hasShared($key) ? $this->sharedData[$key] : $default;
    }

    function setSearchData($data)
    {
        $this->searchData = $data;
    }

    function getSearchData()
    {
        return $this->searchData;
    }

    function hasShared($key)
    {
        return isset($this->sharedData[$key]);
    }

    function getFilter($columnId)
    {
        if (!$this->hasFilter($columnId)) {
            return null;
        }

        $filter = $this->columnFilters[$columnId];

        if (!is_callable($filter) && is_string($filter) && isset(self::$filters[$filter])) {
            $filter = self::$filters[$filter];
        }
        return $filter;
    }

    function setRowsCallback($callback)
    {
        if (!is_callable($callback))
            throw new WException('Callback for rows is not callable: ' . $columnId . '.');
        $this->rowsCallback = $callback;
    }

    function hasRowsCallback()
    {
        return !empty($this->rowsCallback);
    }

    function getRowsCallback()
    {
        return $this->hasFilter($columnId) ? $this->rowsCallback : null;
    }

    function getDataset()
    {
        return $this->dataset;
    }

    function setDataset(array $dataset)
    {
        $this->dataset = $dataset;
    }

    function setHeader($columnId, $label)
    {
        if (!is_array($columnId))
            $columnId = array($columnId);
        foreach ($columnId as $c) {
            $this->headers[$c] = $label;
        }
    }

    function getHeader($columnId)
    {
        return isset($this->headers[$columnId]) ? $this->headers[$columnId] : $columnId;
    }

    function hideColumn($columnId)
    {
        $this->hiddenColumns[] = $columnId;
    }

    function isHidden($columnId)
    {
        return array_search($columnId, $this->hiddenColumns) !== false;
    }

    function showColumn($columnId)
    {
        if ($this->isHidden($columnId)) {
            $key = arraySearch($columnId, $this->hiddenColumns);
            unset($this->hiddenColumns[$key]);
            return true;
        }
        return false;
    }

    function setColumnTemplate($columnId, $cellTemplate)
    {
        if (!is_array($columnId)) {
            $columnId = array($columnId);
        }

        foreach ($columnId as $c) {
            $this->cellsTemplates[$c] = $cellTemplate;
        }
    }

    function hasColumnTemplate($columnId)
    {
        return isset($this->cellsTemplates[$columnId]);
    }

    function getColumnTemplate($columnId)
    {
        return $this->hasColumnTemplate($columnId) ? $this->cellsTemplates[$columnId] : null;
    }

    function setTableAttributes(array $attributes)
    {
        $this->tableAttributes = $attributes;
    }

    function getTableAttributes()
    {
        return $this->tableAttributes;
    }

    function clearTableAttributes()
    {
        $this->tableAttributes = array();
    }

    function clearTableAttribute($attr)
    {
        if ($this->hasTableAttribute($attr)) {
            unset($this->tableAttributes[$attr]);
            return true;
        }
        return false;
    }

    function setTableAttribute($attr, $value)
    {
        $this->tableAttributes[$attr] = $value;
    }

    function hasTableAttribute($attr)
    {
        return isset($this->tableAttributes[$attr]);
    }

    function getTableAttribute($attr, $defaultValue = NULL)
    {
        return $this->hasTableAttribute($attr) ? $this->tableAttributes[$attr] : $defaultValue;
    }

    function setHeaderAttributes($columnId, array $attributes)
    {
        if (!is_array($columnId))
            $columnId = array($columnId);
        foreach ($columnId as $c) {
            $this->headersAttributes[$c] = $attributes;
        }
    }

    function getHeaderAttributes($columnId)
    {
        return isset($this->headersAttributes[$columnId]) ? $this->headersAttributes[$columnId] : array();
    }

    function clearHeaderAttributes($columnId)
    {
        $this->headersAttributes[$columnId] = array();
    }

    function clearHeaderAttribute($columnId, $attr)
    {
        if ($this->hasHeaderAttribute($columnId, $attr)) {
            unset($this->headersAttributes[$columnId][$attr]);
            return true;
        }
        return false;
    }

    function setHeaderAttribute($columnId, $attr, $value)
    {
        if (!is_array($columnId))
            $columnId = array($columnId);
        foreach ($columnId as $c) {
            $this->headersAttributes[$c][$attr] = $value;
        }
    }

    function hasHeaderAttribute($columnId, $attr)
    {
        return isset($this->headersAttributes[$columnId][$attr]);
    }

    function getHeaderAttribute($columnId, $attr, $defaultValue = NULL)
    {
        return $this->hasHeaderAttribute($columnId, $attr) ? $this->headersAttributes[$columnId][$attr] : $defaultValue;
    }

    function setHeaderRowAttributes(array $attributes)
    {
        $this->headerRowAttributes = $attributes;
    }

    function clearHeaderRowAttributes()
    {
        $this->headerRowAttributes = array();
    }

    function clearHeaderRowAttribute($attr)
    {
        if ($this->hasHeaderRowAttribute($attr)) {
            unset($this->headerRowAttributes[$attr]);
            return true;
        }
        return false;
    }

    function setHeaderRowAttribute($attr, $value)
    {
        $this->headerRowAttributes[$attr] = $value;
    }

    function hasHeaderRowAttribute($attr)
    {
        return isset($this->headerRowAttributes[$attr]);
    }

    function getHeaderRowAttribute($attr, $defaultValue = NULL)
    {
        return $this->hasHeaderRowAttribute($attr) ? $this->headerRowAttributes[$attr] : $defaultValue;
    }

    function setColumnAttributes($columnId, array $attributes)
    {
        if (!is_array($columnId))
            $columnId = array($columnId);
        foreach ($columnId as $c) {
            $this->columnsAttributes[$c] = $attributes;
        }
    }

    function clearColumnAttributes($columnId)
    {
        $this->columnsAttributes[$columnId] = array();
    }

    function clearColumnAttribute($columnId, $attr)
    {
        if ($this->hasColumnAttribute($columnId, $attr)) {
            unset($this->columnsAttributes[$columnId][$attr]);
            return true;
        }
        return false;
    }

    function setColumnAttribute($columnId, $attr, $value)
    {
        if (!is_array($columnId))
            $columnId = array($columnId);
        foreach ($columnId as $c) {
            $this->columnsAttributes[$c][$attr] = $value;
        }
    }

    function hasColumnAttribute($columnId, $attr)
    {
        return isset($this->columnsAttributes[$columnId][$attr]);
    }

    function getColumnAttribute($columnId, $attr, $defaultValue = NULL)
    {
        return $this->hasColumnAttribute($columnId, $attr) ? $this->columnsAttributes[$columnId][$attr] : $defaultValue;
    }

    function getColumnAttributes($columnId)
    {
        return isset($this->columnsAttributes[$columnId]) ? $this->columnsAttributes[$columnId] : array();
    }

    function setWeight($columnId, $weight)
    {
        if (!is_array($columnId))
            $columnId = array($columnId);
        foreach ($columnId as $c) {
            $this->weights[$c] = $weight;
        }
    }

    function getWeight($columnId)
    {
        return isset($this->weights[$columnId]) ? $this->weights[$columnId] : null;
    }

    function addColumn($columnId, $columnHeader, $columnTemplate, $columnWeight = null)
    {
        $this->virtualColumns[$columnId] = $columnId;
        $this->setWeight($columnId, $columnWeight);
        $this->setHeader($columnId, $columnHeader);
        $this->setColumnTemplate($columnId, $columnTemplate);
    }

    public function removeColumn($columnId)
    {

        unset($this->virtualColumns[$columnId]);
        unset($this->weights[$columnId]);
        unset($this->headersAttributes[$columnId]);
        unset($this->rowsAttributes[$columnId]);
        unset($this->rowsAttributes[$columnId]);
        unset($this->rowsAttributes[$columnId]);
        unset($this->rowsCallback[$columnId]);
        $this->hideColumn($columnId);
    }


    /**
     * Ver nth-child no css
     *
     * Ex: 2n+1, 2n, n, -n+3, 2n-2, 3
     */
    function setRowAttributes($nthSelector, array $attributes)
    {
        if (!$this->isValidNthSelector($nthSelector))
            throw new WException('Invalid nth-selector:' . $nthSelector);
        $this->rowsAttributes[$nthSelector] = $attributes;
    }

    function clearRowAttributes()
    {
        $this->rowsAttributes = array();
    }

    function setRowAttribute($nthSelector, $attr, $value)
    {
        $this->rowsAttributes[$nthSelector][$attr] = $value;
    }

    function hasRowAttribute($rowNumber, $attr)
    {
        if (!filter_var($rowNumber, FILTER_VALIDATE_INT))
            return false;
        $attrs = $this->getRowAttributes($rowNumber);
        return isset($attrs[$attr]);
    }

    function getRowAttributes($rowNumber)
    {
        if (!filter_var($rowNumber, FILTER_VALIDATE_INT))
            return array();

        $appendableAttrs = array('class', 'rel', 'style');

        $outAttrs = array();

        foreach ($this->rowsAttributes as $selector => $attrs) {
            if (!$this->matchNthSelector($selector, $rowNumber))
                continue;
            foreach ($attrs as $attr => $value) {
                if (isset($outAttrs[$attr]) && inArray($attr, $appendableAttrs)) {
                    $outAttrs[$attr] .= ' ' . $value;
                    continue;
                }
                $outAttrs[$attr] = $value;
            }
        }
        return $outAttrs;
    }

    function render($echo = TRUE)
    {
        $table = '<table' . $this->parseAttrs($this->tableAttributes) . '>';
        $table .= '<thead>';

        $headers   = array();
        $headerIdx = 0;
        if (!empty($this->dataset)) {
            $tmpHeader = array_keys($this->dataset[0]);
            foreach ($tmpHeader as $header) {
                if ($this->isHidden($header))
                    continue;

                $weight    = $this->getWeight($header);
                if ($weight === null)
                    $weight    = $headerIdx + 1;
                $headers[] = array(
                    'id'     => $header,
                    'idx'    => $headerIdx,
                    'weight' => $weight
                );
                $headerIdx++;
            }
        }

        if (!empty($this->virtualColumns)) {
            foreach ($this->virtualColumns as $id) {
                $weight    = $this->getWeight($id);
                if ($weight === null)
                    $weight    = $headerIdx + 1;
                $headers[] = array(
                    'id'     => $id,
                    'idx'    => $headerIdx,
                    'weight' => $weight
                );
                $headerIdx++;
            }
        }

        if (empty($headers))
            return '';

        uasort($headers, array($this, 'sortHeadersSorter'));

        $headerRow = '<tr' . $this->parseAttrs($this->headerRowAttributes) . '>';

        $visibleHeaders = array_keys($this->getVisibleHeaders());

        foreach ($headers as $header) {
            $headerId = $header['id'];
            //$renderOrder = in_array($headerId, $visibleHeaders);
            $renderOrder = false; //TODO: fazer a ordenação funcionar
            $th       = '<th'
                . $this->parseAttrs($this->getHeaderAttributes($headerId), $headerId)
                . '>';

            if ($renderOrder) {
                $dataOrder = $this->getHeaderAttribute($headerId, 'data-order');
                $order = $headerId;
                if (!empty($dataOrder) && $dataOrder == 'asc') {
                    $order .= '_desc';
                }
                $variables = array_merge(array('order' => $order), $this->searchData);
                $th .= sprintf('<a href="?%s">', http_build_query($variables));
            }

            $th .= str_replace(self::PH_COLUMN, $headerId, $this->getHeader($headerId));

            if ($renderOrder) {
                $th .= ' <span class="icon-sort"></span></a>';
            }

            $th .= '</th>';
            $headerRow .= $th;
        }

        $headerRow .= '</tr>';

        $table .= $headerRow;

        unset($headerRow);

        $table .= '</thead>';

        $tbody = '<tbody>';
        if (!empty($this->dataset) || is_array($this->dataset)) {
            $dataset  = $this->dataset;
            $rowCount = count($dataset);
            $rowIndex = 0;
            foreach ($dataset as $rawRow) {
                $rowIndex++;
                //add shared data
                $rawRow = $this->sharedData + $rawRow;
                if ($this->hasRowsCallback()) {
                    call_user_func_array($this->rowsCallback, array(
                        &$rawRow,
                        $this,
                        $rowIndex,
                        $rowCount
                        )
                    );
                }
                $row = '<tr' . $this->parseAttrs($this->getRowAttributes($rowIndex), null, $rawRow, null, $rowIndex, $rowCount) . '>';
                foreach ($headers as $column) {
                    $columnId = $column['id'];
                    $value    = isset($rawRow[$columnId]) ? $rawRow[$columnId] : null;
                    $cellTemplate = $this->getColumnTemplate($columnId);
                    $cellValue    = $this->parseCellTemplate($cellTemplate, $columnId, $rawRow, $value, $rowIndex, $rowCount);
                    if ($this->hasFilter($columnId)) {
                        $cellValue    = call_user_func_array(
                            $this->getFilter($columnId), array(
                            $cellValue,
                            $rawRow,
                            $columnId,
                            $this,
                            $rowIndex,
                            $rowCount
                            )
                        );
                    }
                    $cell  = '<td' . $this->parseAttrs($this->getColumnAttributes($columnId), $columnId, $rawRow, $cellValue, $rowIndex, $rowCount) . '>';
                    $cell .= $cellValue . '</td>';
                    $row  .= $cell;
                    unset($cell);
                }
                $row .= '</tr>';
                $tbody .= $row;
            }
        }
        $tbody .= '</tbody>';

        $table .= $tbody;
        unset($tbody);

        $table .= '</table>';

        if ($echo)
            echo $table;
        else
            return $table;
    }


    public function getVisibleHeaders()
    {
        $headers   = array();
        $headerIdx = 0;
        if (!empty($this->dataset)) {
            $tmpHeader = array_keys($this->dataset[0]);
            foreach ($tmpHeader as $header) {
                if ($this->isHidden($header))
                    continue;

                $weight    = $this->getWeight($header);
                if ($weight === null) {
                    $weight    = $headerIdx + 1;
                }
                $headers[$header] = array(
                    'id'     => $header,
                    'idx'    => $headerIdx,
                    'weight' => $weight
                );
                $headerIdx++;
            }
        }

        uasort($headers, array($this, 'sortHeadersSorter'));

        return $headers;
    }

    protected function sortHeadersSorter($a, $b)
    {
        if ($a['weight'] > $b['weight'])
            return 1;
        else if ($a['weight'] < $b['weight'])
            return -1;
        else {
            if ($a['idx'] > $b['idx'])
                return 1;
            elseif ($a['idx'] < $b['idx'])
                return -1;
        }
        return 0;
    }

    protected function parseCellTemplate($template, $headerId = null, $rowData = null, $cellValue = null, $rowIndex = null, $rowCount = null)
    {
        if (empty($template)) {
            return $cellValue;
        }
        if (\is_callable($template)) {
            $template = \call_user_func($template, $rowData);
        }
        if (!is_null($headerId))
            $template = str_replace(self::PH_COLUMN, $headerId, $template);
        if (!is_null($rowIndex))
            $template = str_replace(self::PH_CURRENT_ROW, $rowIndex, $template);
        if (!is_null($rowCount))
            $template = str_replace(self::PH_ROW_COUNT, $rowCount, $template);
        if (!is_null($cellValue))
            $template = str_replace(self::PH_VALUE, $cellValue, $template);
        if (is_array($rowData)) {
            foreach ($rowData as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $vk => $vv) {
                        $key      = sprintf(self::PH_DATASET_VALUE, $k . '.' . $vk);
                        $template = str_replace($key, $vv, $template);
                    }
                } else {
                    $key      = sprintf(self::PH_DATASET_VALUE, $k);
                    $template = str_replace($key, $v, $template);
                }
            }
        }
        return $template;
    }

    protected function parseAttrs($attrs, $headerId = null, $rowData = null, $cellValue = null, $rowIndex = null, $rowCount = null)
    {
        $out = '';
        foreach ($attrs as $attr => $value) {
            if (!is_null($headerId))
                $value = str_replace(self::PH_COLUMN, $headerId, $value);
            if (!is_null($rowIndex))
                $value = str_replace(self::PH_CURRENT_ROW, $rowIndex, $value);
            if (!is_null($rowCount))
                $value = str_replace(self::PH_ROW_COUNT, $rowCount, $value);
            if (!is_null($cellValue))
                $value = str_replace(self::PH_VALUE, $cellValue, $value);
            if (is_array($rowData)) {
                foreach ($rowData as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $vk => $vv) {
                            $key   = sprintf(self::PH_DATASET_VALUE, $k . '.' . $vk);
                            $value = str_replace($key, $vv, $value);
                        }
                    } else {
                        $key   = sprintf(self::PH_DATASET_VALUE, $k);
                        $value = str_replace($key, $v, $value);
                    }
                }
            }
            $out .= ' ' . $attr . '="' . $value . '"';
        }
        return $out;
    }

    protected final function matchNthSelector($selector, $number)
    {
        $selector = str_replace(' ', '', $selector);
        $regex    = '#^(?:(?P<minus>-)?(?P<lOperand>n|[0-9]+(n)?)(?:(?P<operator>\+|-)(?P<rOperand>[0-9]+))?|(?P<especial>odd|even))$#i';
        if (!pregMatchAll($regex, $selector, $matches, PREGSETORDER))
            return false;
        $matches  = $matches[0];
        if (!empty($matches['especial'])) {
            switch ($matches['especial']) {
                case 'odd':
                    return $number % 2 == 1;
                case 'even':
                    return $number % 2 == 0;
            }
        } else {
            $lModifier = 1;
            if (!empty($matches['minus']))
                $lModifier = -1;
            $lOperand  = $matches['lOperand'];
            $nIterate  = false;
            if (pregMatch('#(?P<operand>[0-9]+)?n#', $lOperand, $lMatches)) {
                $lOperand = isset($lMatches['operand']) ? $lMatches['operand'] : 1;
                $nIterate = true;
            }


            $rModifier = 1;
            $rOperand  = 0;

            if (isset($matches['operator'])) {
                if ($matches['operator'] == '-')
                    $rModifier = -1;
                $rOperand  = $matches['rOperand'];
            }

            if (!$nIterate)
                return (($lModifier * $lOperand) + ($rModifier * $rOperand)) == $number;
            else {
                $n         = 0;
                $result    = 0;
                $ascending = $lModifier == 1;
                $continue  = true;
                while ($continue) {
                    if ($ascending && $result > $number)
                        break;
                    if (!$ascending && $result < 0)
                        break;
                    $result = (($lModifier * $lOperand * $n) + ($rModifier * $rOperand));

                    if ($result == $number)
                        return true;

                    $n++;
                }
                return false;
            }
        }
    }

    public static function addFilter($name, callable $filter)
    {
        self::$filters[$name] = $filter;
    }

    protected final function isValidNthSelector($selector)
    {
        $regex    = '#^(-?(n|[0-9]+(n)?)((\+|-)[0-9]+)?|(odd|even))$#i';
        $selector = str_replace(' ', '', $selector);
        return (bool) pregMatch($regex, $selector);
    }

    function __toString()
    {
        try {
            return $this->render(false);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
