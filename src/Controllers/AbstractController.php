<?php

namespace WpBreeze\Controllers;

use WpBreeze\Application;

abstract class AbstractController
{
    /**
     * @var WpBreeze\Application
     */
    protected $application = null;

    /**
     * @var Timber\Adapter
     */
    protected $timber = null;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param WpBreeze\Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->timber = $application->getTimber();

        // Set default parameters
        $this->timber->setParameter('application', $application);
    }

    /**
     * @return string
     */
    public function render()
    {
        return '';
    }

    /**
     * @param string $class
     * @return AbstratViewHelper
     */
    public function addViewHelper($class)
    {
        $viewHelper = new $class();
        $viewHelper->application = $this->application;

        $this->timber->addFunction($viewHelper->name, function (...$args) use (&$viewHelper) {
            return $viewHelper->renderWithArgs($args);
        });

        return $viewHelper;
    }

    /**
     *
     * @param mixed $data
     * @return $this
     */
    public function setData($data) {
        $this->data = $data;

        return $this;
    }
}
