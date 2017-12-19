<?php

namespace WpBreeze\ViewHelpers;

class AjaxUrlViewHelper extends AbstractViewHelper
{
    /**
     * {@inheritDoc}
     */
    public $name = 'ajax_url';

    /**
     * @param string $action
     * @return string
     */
    public function render($action)
    {
        return rtrim(admin_url(), '/') . '/admin-ajax.php?action=' . $action;
    }
}
