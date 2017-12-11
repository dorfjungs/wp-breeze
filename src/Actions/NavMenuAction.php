<?php

namespace WpBreeze\Actions;

class NavMenuAction extends AbstractAction
{
    /**
     * @var string
     */
    public $name = 'nav-menu';

    /**
     * @var string
     */
    public $hook = 'after_setup_theme';

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        if (array_key_exists('menus', $params) && is_array($params['menus'])) {
            register_nav_menus($params['menus']);
        }
    }
}
