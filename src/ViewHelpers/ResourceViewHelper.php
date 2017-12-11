<?php

namespace WpBreeze\ViewHelpers;

class ResourceViewHelper extends AbstractViewHelper
{
    /**
     * {@inheritDoc}
     */
    public $name = 'resource';

    /**
     * @param string $path
     */
    public function render($path = '')
    {
        return rtrim($this->application->getResourceUrl(), '/') . '/' . ltrim($path, '/');
    }
}
