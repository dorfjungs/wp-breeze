<?php

namespace WpBreeze\Timber;

use Timber;

class Adapter
{
    /**
     * @var Timber\Timber
     */
    private $timber = null;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @param array $paths
     */
    public function __construct($paths = [])
    {
        $this->timber = new Timber\Timber();

        foreach ($paths as $path) {
            Timber::$locations[] = $path;
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function timberShortcut($value)
    {
        if (is_string($value) && substr($value, 0, 2) == '::') {
            return call_user_func('Timber' . $value);
        }

        return $value;
    }

    /**
     * @param string $name
     * @param \callback $function
     */
    public function addFunction($name, callable $function)
    {
        add_action('timber/twig/functions', function ($twig) use (&$name, $function) {
            $twig->addFunction(new \Twig_SimpleFunction($name, $function));

            return $twig;
        });
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setParameter($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @param array|string $templates
     * @param array $arguments
     * @return string
     */
    public function render($templates, $arguments = [], $queryPosts = [])
    {
        if ( ! empty($query_posts)) {
            query_posts($queryPosts);
        }

        if (is_string($templates)) {
            $templates = [$templates];
        }

        ob_start();

        $context = Timber::get_context();
        $params = array_replace_recursive($arguments, $this->params);

        foreach ($params as $key => $value) {
            $context[$key] = $this->timberShortcut($value);
        }

        Timber::render($templates, $context);

        return ob_get_clean();
    }
}
