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
    public function render($action, $args = [])
    {
        $argsStr = '';

        foreach ($args as $name => $value) {
            $argsStr .= $name . '=' . $value;
        }

        return rtrim(admin_url(), '/') . '/admin-ajax.php?action=' . $action . (!empty($argsStr) ? '&' : '') . $argsStr;
    }
}
