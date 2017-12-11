<?php

namespace WpBreeze\Actions;

class LoginHeaderTitleAction extends AbstractAction
{
    /**
     * {@inheritDoc}
     */
    public $name = 'login-header-title';

    /**
     * {@inheritDoc}
     */
    public $hook = 'login_headertitle';

    /**
     * {@inheritDoc}
     */
    public $type = self::HOOK_FILTER;

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        return wp_title('', false);
    }
}
