<?php

namespace WpBreeze\ViewHelpers;

class Base64ViewHelper extends AbstractViewHelper
{
    /**
     * {@inheritDoc}
     */
    public $name = 'base64';

    /**
     * @param string|array $data
     * @return string
     */
    public function render($data, $encode = true)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        return $encode ? base64_encode($data) : base64_decode($data);
    }
}
