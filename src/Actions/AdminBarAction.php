<?php

namespace WpBreeze\Actions;

class AdminBarAction extends AbstractAction
{
    /**
     * {@inheritDoc}
     */
    public $name = 'admin-bar';

    /**
     * {@inheritDoc}
     */
    public $hook = 'after_setup_theme';

    /**
     * @var array
     */
    public $userRoles = [
        'super_admin',
        'administrator',
        'editor',
        'author',
        'contributor'
    ];

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        $currentUser = wp_get_current_user();

        if (array_key_exists('disable', $params) && true === $params['disable'] ||
            ($currentUser && ! array_intersect($currentUser->roles, $this->userRoles))) {
            add_filter('show_admin_bar', '__return_false');
        }
    }
}
