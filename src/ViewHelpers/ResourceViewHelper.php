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
    public function render($path = '', $usePath = false)
    {
        return rtrim($this->application->getResourceUrl($usePath), '/') . '/' . ltrim($path, '/');
    }
}
