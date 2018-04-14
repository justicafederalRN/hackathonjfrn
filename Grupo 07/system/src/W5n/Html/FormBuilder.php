<?php
namespace W5n\Html;

class FormBuilder
{
    protected $rows = array();
    protected $tabs = array();
    protected $activeTab = null;

    public function rawContent($content)
    {
        $this->rows[] = $content;
    }

    public function addTab($id, $title, $icon = null, $url = null, $active = false)
    {
        $this->tabs[$id] = array(
            'title' => $title,
            'icon'  => $icon,
            'url'   => $url
        );
        if ($active) {
            $this->setActiveTab($id);
        }
    }

    public function removeTab($id)
    {
        unset($this->tabs[$id]);
    }

    public function openTab($id)
    {
        $classes = array('tab-pane');
        if ($this->isActiveTab($id)) {
            $classes[] = 'active';
        }
        $this->rawContent(
            sprintf('<div class="%s" id="tab-%s">', implode(' ', $classes), $id)
        );
    }

    public function closeTab()
    {
        $this->rawContent('</div>');
    }

    public function hasTab($id)
    {
        return isset($this->tabs[$id]);
    }

    public function isActiveTab($id)
    {
        return $this->activeTab == $id;
    }

    public function setActiveTab($id)
    {
        $this->activeTab = $id;
    }

    public function openFieldset($legend)
    {
        $this->rows[] = '<h3 class="header smaller lighter">' . $legend . '</h3>';
        $this->rows[] = '<fieldset class="well">';
    }

    public function closeFieldset()
    {
        $this->rows[] = '</fieldset>';
    }

    public function row(array $row)
    {
        $this->rows[] = $row;
    }

    public function getLayout()
    {
        if (empty($this->tabs)) {
            return $this->rows;
        }

        $tabs         = '<ul class="nav nav-tabs tab-bricky">';
        $itemTemplate = '<li%s><a href="#tab-%s" data-toggle="tab">%s</a></li>';
        foreach ($this->tabs as $id => $info) {
            $content = $info['title'];
            $extra   = '';
            if ($this->isActiveTab($id)) {
                $extra = ' class="active"';
            }
            if (!empty($info['icon'])) {
                $content = sprintf('<i class="%s"></i> ', $info['icon']) . $content;
            }

            $tabs .= sprintf($itemTemplate, $extra, $id, $content);
        }
        $tabs .= '</ul><div class="tab-content form-nav">';

        return array_merge(array($tabs), $this->rows, array('</div>'));
    }

}
