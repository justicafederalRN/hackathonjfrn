<?php
namespace W5n\Model\Field;

interface Renderer 
{
    public function toHtml(Field $field);
}