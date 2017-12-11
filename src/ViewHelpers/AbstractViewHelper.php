<?php

namespace WpBreeze\ViewHelpers;

use WpBreeze\Exceptions\RenderFunctionUndefinedException;

abstract class AbstractViewHelper
{
    /**
     * @var string
     */
    public $name = '';

    /**
     * @var WpBreeze\Application
     */
    public $application = null;

    /**
     * @param string $class
     * @param array ...$args
     * @return mixed
     */
    public function viewHelper($class, ...$args)
    {
        $viewHelper = new $class;

        return $viewHelper->renderWithArgs($args);
    }

    /**
     * @param array $args
     * @return string
     */
    public function renderWithArgs($args = [])
    {
        if (method_exists($this, 'render')) {
            return call_user_func_array([$this, 'render'], $args);
        }

        throw new RenderFunctionUndefinedException(
                sprintf(
                    'The function "render" was not found for the view helper "%s"',
                    $this->name
                )
            );
    }
}
