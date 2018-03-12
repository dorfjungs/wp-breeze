<?php

namespace WpBreeze\ViewHelpers;

class DebugViewHelper extends AbstractViewHelper
{
    /**
     * {@inheritDoc}
     */
    public $name = 'debug';

    /**
     * @param string $x
     */
    public function render($x)
    {
        var_dump($x);
        exit;
    }
}
