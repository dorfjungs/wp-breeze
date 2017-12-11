<?php

namespace WpBreeze\Actions;

abstract class AbstractAction
{
    /**
     * @var integer
     */
    const HOOK_ACTION = 1;

    /**
     * @var integer
     */
    const HOOK_FILTER = 2;

    /**
     * @var string
     */
    const HOOK_SEPARATOR = '.';

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string
     */
    public $hook = '';

    /**
     * @var array $params
     */
    public $hookType = self::HOOK_ACTION;

    /**
     * @var WpBreeze\Application
     */
    public $application = null;

    /**
     * @var boolean
     */
    private $locked = false;

    /**
     * @param array $params
     * @return mixed
     */
    abstract public function run($params = []);

    /**
     * @param array $params
     * @return array
     */
    public function prepare($params = [])
    {
        return $params;
    }

    /**
     * @param boolean $locked
     * @return void
     */
    public function lock($locked = true)
    {
        $this->locked = $locked;
    }

    /**
     * @return boolean
     */
    public function isLocked()
    {
        return $this->locked;
    }
}
