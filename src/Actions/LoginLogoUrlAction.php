<?php

namespace WpBreeze\Actions;

class LoginLogoUrlAction extends AbstractAction
{
    /**
     * {@inheritDoc}
     */
    public $name = 'login-logo-url';

    /**
     * {@inheritDoc}
     */
    public $hook = 'login_headerurl';

    /**
     * {@inheritDoc}
     */
    public $type = self::HOOK_FILTER;

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        return home_url();
    }
}
