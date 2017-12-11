<?php

namespace WpBreeze;

class Bootstrap
{
    /**
     * @var array
     */
    private $actions = [];

    /**
     * @var array
     */
    private $actionParams = [];

    /**
     * @var array
     */
    private $beforeActionCallbacks = [];

    /**
     * @param string $class
     * @param array $params
     * @return self
     */
    public function registerAction($class, $params = [])
    {
        $action = new $class();

        if (array_key_exists($action->name, $this->actions)) {
            throw new Exceptions\InvalidActionException(
                sprintf('Action with the name "%s" was already registerd', $action->name)
            );
        }

        $this->actions[$action->name] = $action;
        $this->actionParams[$action->name] = $params;
    }

    /**
     * @param array $params
     * @return void
     */
    public function runActions($application, $params = [])
    {
        $actionParams = array_replace_recursive($this->actionParams, $params);

        foreach ($this->actions as $action) {
            $action->application = $application;
            $params = array_key_exists($action->name, $actionParams)
                ? $actionParams[$action->name]
                : [];

            $params = $action->prepare($params);

            if ( ! is_array($params)) {
                throw new Exceptions\InvalidParamsException(
                    sprintf(
                        'The prepare function of the "%s" action
                        needs to return the parameters as an array',
                        $action->name
                    )
                );
            }

            if (empty($action->hook)) {
                $this->runAction($action, $params);
            } else {
                $this->executeHooks($action, function ($args) use ($action, $params) {
                    if ( ! empty($args)) {
                        $params['args'] = $args;
                    }

                    return $this->runAction($action, $params);
                });
            }
        }
    }

    /**
     * @return void
     */
    public function beforeActionRun($callback)
    {
        $this->beforeActionCallbacks[] = $callback;
    }

    /**
     * @param Actions\AbstractAction $action
     * @param array $params
     * @return mixed
     */
    private function runAction(&$action, &$params = [])
    {
        foreach ($this->beforeActionCallbacks as $callback) {
            $callback($action, $params);
        }

        $ret = null;

        if ( ! $action->isLocked()) {
            $ret = $action->run($params);
        }

        $action->application = null;

        return $ret;
    }

    /**
     * @param Actions\AbstractAction $action
     * @param callable $callback
     * @return void
     */
    private function executeHooks(Actions\AbstractAction $action, callable $callback)
    {
        $parts = explode($action::HOOK_SEPARATOR, $action->hook);
        $fnc = '';

        if ($action->hookType == $action::HOOK_ACTION) {
            $fnc = 'add_action';
        } elseif ($action->hookType == $action::HOOK_FILTER) {
            $fnc = 'add_filter';
        }

        $this->executeHookCtx($parts, $callback, $fnc);
    }

    /**
     * @param array $parts
     * @param callable $callback
     * @param string $fnc
     * @return mixed
     */
    private function executeHookCtx($parts, callable $callback, $fnc)
    {
        $fnc(array_shift($parts), function () use (&$parts, $callback, $fnc) {
            if (count($parts) > 0) {
                return $this->executeHookCtx($parts, $callback, $fnc);
            }

            return $callback(func_get_args());
        });
    }
}
