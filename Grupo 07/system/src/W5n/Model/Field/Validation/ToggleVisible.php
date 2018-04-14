<?php
namespace W5n\Model\Field\Validation;


class ToggleVisible extends \W5n\Model\Field\Validation\Validation
{
    protected $fields         = [];
    protected $otherSelectors = [];
    
    
    public function __construct(array $fields = [], array $otherSelectors = [])
    {
        $this->fields         = $fields;
        $this->otherSelectors = $otherSelectors;
    }

    public function getErrorMessage(\W5n\Model\Field\Field $field, $operation)
    {
        return null;
    }

    public function validate($value, \W5n\Model\Field\Field $field, $operation)
    {
        return true;
    }
    
    public function beforeRenderField(\W5n\Html\Tag $html, \W5n\Model\Field\Field $field)
    {
        if (!($field instanceof \W5n\Model\Field\Boolean) || (empty($this->fields) && $this->otherSelectors)) {
            return;
        }
        
        
        
        $tagName  = $html->getName();
        $name     = $html->getAttr('name');
        $selector = $tagName.'[name=' . $name . ']';
   
        $toShow = [];
        $toHide = [];
        
        foreach ($this->fields as $name => $visibility) {
            if ($visibility) {
                $toShow[] = '#field_' . $name;
            } else {
                $toHide[] = '#field_' . $name;
            }
        }
        
        foreach ($this->otherSelectors as $sel => $visibility) {
            if ($visibility) {
                $toShow[] = $sel;
            } else {
                $toHide[] = $sel;
            }
        }
        
        $toShowSelectorOn = '';
        $toShowSelectorOff = '';
        $toHideSelectorOn = '';
        $toHideSelectorOff = '';
        
        if (!empty($toShow)) {
            $toShowSelectorOn  = '$("' . implode(', ', $toShow) . '").show();';
            $toShowSelectorOff = '$("' . implode(', ', $toShow) . '").hide();';
        }
        
        if (!empty($toHide)) {
            $toHideSelectorOn  = '$("' . implode(', ', $toHide) . '").show();';
            $toHideSelectorOff = '$("' . implode(', ', $toHide) . '").hide();';
        }
        
        
        $var = <<<JS
            <script type="text/javascript">
                $(document).ready(function() {
                    $('$selector').click(function() {
                        if ($(this).is(':checked')) {
                            $toShowSelectorOn
                            $toHideSelectorOff
                        } else {
                            $toShowSelectorOff
                            $toHideSelectorOn
                        }
                    });
                    if ($('$selector').is(':checked')) {
                        $toShowSelectorOn
                        $toHideSelectorOff
                    } else {
                        $toShowSelectorOff
                        $toHideSelectorOn
                    }
                });
                
            </script>
JS;
        
        \View::startGlobal('footer');
        echo $var;
        \View::endGlobal();
    }

}
