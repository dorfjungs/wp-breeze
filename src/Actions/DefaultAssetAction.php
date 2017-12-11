<?php

namespace WpBreeze\Actions;

class DefaultAssetAction extends AbstractAssetAction
{
    /**
     * @var string
     */
    public $name = 'default-asset';

    /**
     * @var string
     */
    public $hook = 'init.wp_enqueue_scripts';


    public function __construct()
    {
        $this->lock(is_admin());
    }

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        parent::run($params);
    }
}
