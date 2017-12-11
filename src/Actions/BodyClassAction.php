<?php

namespace WpBreeze\Actions;

class BodyClassAction extends AbstractAction
{
    /**
     * {@inheritDoc}
     */
    public $name = 'body-class';

    /**
     * {@inheritDoc}
     */
    public $hook = 'body_class';

    /**
     * {@inheritDoc}
     */
    public $hookType = self::HOOK_FILTER;

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        return array_key_exists('classes', $params) ? $params['classes'] : [];
    }
}
