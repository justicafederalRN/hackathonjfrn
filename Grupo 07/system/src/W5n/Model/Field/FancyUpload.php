<?php
namespace W5n\Model\Field;

use W5n\Html\HtmlBuilder;

class FancyUpload extends Upload
{
    /*
     * <div data-provides="fileupload" class="fileupload fileupload-new">
            <span class="btn btn-file btn-light-grey">
     *          <i class="fa fa-folder-open-o"></i> 
     *          <span class="fileupload-new">Select file</span>
     *          <span class="fileupload-exists">Change</span>
                <input type="file">
            </span>
            <span class="fileupload-preview"></span>
            <a style="float: none" data-dismiss="fileupload" class="close fileupload-exists" href="#">
                ×
            </a>
        </div>
     */
    public function toHtml(Field $field)
    {
        $input     = parent::toHtml($field);
        
        $container       = HtmlBuilder::tag('div', array('class' => 'fileupload fileupload-new', 'data-provides' => 'fileupload'));        
        $buttonContainer = HtmlBuilder::tag('span', array('class' => 'btn btn-file btn-light-grey'));
        $buttonContainer->appendTag('i', array('class' => 'fa fa-folder-open-o'));
        $buttonContainer->appendTag('span', array('class' => 'fileupload-new'))->appendText('Selecionar arquivo...');
        $buttonContainer->appendTag('span', array('class' => 'fileupload-exists'))->appendText('Alterar arquivo...');
        $buttonContainer->appendChild($input);
        $container->appendChild($buttonContainer);
        $container->appendTag('span', array('class' => 'fileupload-preview'));
        $container->appendTag(
            'a', 
            array(
                'style' => 'float:none', 
                'data-dismiss' => 'fileupload', 
                'class' => 'close fileupload-exists',
                'href'  => '#'
            )
        )->appendText('x');
        
        return $container;
    }
}