<?php

namespace WpBreeze\Actions;

class LoginAssetAction extends AbstractAssetAction
{
    /**
     * @var string
     */
    public $name = 'login-asset';

    /**
     * @var string
     */
    public $hook = 'init.login_enqueue_scripts';

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        parent::run($params);
    }
}
