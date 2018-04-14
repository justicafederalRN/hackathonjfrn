<?php
namespace W5n\Model\Field;


class Video extends Url
{
    
    function __construct($name, $label)
    {
        parent::__construct($name, $label);
    }
    
    public function beforeSave($operation)
    {
        parent::beforeSave($operation);
        $embed = new \Embed\Embed();
        $value = $this->getValue();
        
        if (!empty($value)) {
            $this->setOption('url', $value);
            $info  = $embed->create($value);
            $this->setValue(serialize($this->encodeInfo($info)));
        }
    }
    
    protected function encodeInfo(\Embed\Adapters\AdapterInterface $adapter) 
    {
        return [
            'code'         => $adapter->getCode(),
            'url'          => $adapter->getUrl(),
            'image'        => $adapter->getImage(),
            'image_width'  => $adapter->getImageWidth(),
            'image_height' => $adapter->getImageHeight(),
            'title'        => $adapter->getTitle(),
            'tags'         => $adapter->getTags(),
            'description'  => $adapter->getDescription()
        ];
    }
    
    
    
    public function afterSave($success, $operation)
    {
        parent::afterSave($success, $operation);
        $this->setValue($this->getOption('url', null));
    }
    
    public function afterModelPopulate($operation)
    {
        parent::afterModelPopulate($operation);
        if ($operation != \W5n\Model\Model::OP_DB_POPULATE)  {
            return;
        }
        $value = $this->getValue();
        if (!empty($value)) {
            try {
                $info = unserialize($value);
                $this->setOption('videoInfo', $info);
                $this->setValue($info['url']);
            } catch (\Exception $ex) {
            }
        }
    }
    
    public function toHtml(Field $field)
    {
        $input = parent::toHtml($field);
        $input->addClass('input-video');
        return $input;
    }
    
    
}

