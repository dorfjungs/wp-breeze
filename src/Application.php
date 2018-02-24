<?php

namespace WpBreeze;

use WpBreeze\Controllers\AbstractController;

class Application
{
    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var string
     */
    protected $baseUrl = '';

    /**
     * @var string
     */
    protected $basePath = '';

    /**
     * @var string
     */
    protected $templatePath = 'templates';

    /**
     * @var string
     */
    protected $resourcePath = 'resources';

    /**
     * @var Timber\Adapter
     */
    protected $timber = null;

    /**
     * @var array
     */
    private $viewHelpers = [
        ViewHelpers\ResourceViewHelper::class,
        ViewHelpers\Base64ViewHelper::class,
        ViewHelpers\ContentViewHelper::class,
        ViewHelpers\AjaxUrlViewHelper::class
    ];

    /**
     * @var array
     */
    private $actions = [
        Actions\SecurityAction::class,
        Actions\DefaultAssetAction::class,
        Actions\AcfBreezeAction::class,
        Actions\BodyClassAction::class,
        Actions\AdminBarAction::class,
        Actions\ThemeSupportAction::class,
        Actions\ImageSizeAction::class,
        Actions\NavMenuAction::class,
        Actions\LoginAssetAction::class,
        Actions\LoginLogoUrlAction::class,
        Actions\LoginHeaderTitleAction::class,
        Actions\AcfFieldTitleAction::class
    ];

    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->bootstrap = new Bootstrap($id);
        $this->baseUrl = get_stylesheet_directory_uri();
        $this->basePath = get_template_directory();
        $this->timber = new Timber\Adapter([
            $this->basePath . '/' . $this->templatePath
        ]);

        static::$instances[$id] = $this;
    }

    /**
     * @param string $id
     * @return self
     */
    public static function get($id = '')
    {
        if ( ! array_key_exists($id, static::$instances)) {
            throw new Exceptions\ApplicationNotFoundException(
                'Application with the id "%s" not found',
                $id
            );
        }

        return static::$instances[$id];
    }

    /**
     * @param array $actions
     * @param boolean $overrideActions
     * @return self
     */
    public function bootstrap($actions = [], $overrideActions = false)
    {
        $this->beforeBootstrap();

        if ($overrideActions) {
            $this->actions = $actions;
        } else {
            foreach ($actions as $class) {
                $this->actions[] = $class;
            }
        }

        foreach ($this->actions as $class) {
            $this->bootstrap->registerAction($class);
        }

        // Listen for actions to be called
        $this->bootstrap->beforeActionRun(function (&$action, &$params) {
            $parseMethod = sprintf('before%sAction', $this->parseFunctionName($action->name));

            if (method_exists($this, $parseMethod)) {
                $this->{$parseMethod}($action, $params);
            }
        });

        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    private function parseFunctionName($name)
    {
        $name = lcfirst(str_replace('-', '', ucwords($name, '-')));
        $name = ucfirst($name);

        return $name;
    }

    /**
     * @param string $classes
     * @return void
     */
    protected function addActions($classes)
    {
        foreach ($classes as $class) {
            if ( ! in_array($class, $this->actions)) {
                $this->actions[] = $class;
            }
        }
    }

    /**
     * @param string $class
     * @return void
     */
    protected function addAction($class)
    {
        $this->addActions([$class]);
    }

    /**
     * @param string $classes
     * @return void
     */
    protected function removeActions($classes)
    {
        foreach ($classes as $class) {
            if (in_array($class, $this->actions)) {
                $index = array_search($class, $this->actions);

                if ($index > -1) {
                    unset($this->actions[$index]);
                    $this->actions = array_values($this->actions);
                }
            }
        }
    }

    /**
     * @param string $class
     * @return void
     */
    protected function removeAction($class)
    {
        $this->removeActions([$class]);
    }

    /**
     * @param string $classes
     * @return void
     */
    protected function addViewHelpers($classes)
    {
        foreach ($classes as $class) {
            if ( ! in_array($class, $this->viewHelpers)) {
                $this->viewHelpers[] = $class;
            }
        }
    }

    /**
     * @param string $class
     * @return void
     */
    protected function addViewHelper($class)
    {
        $this->addViewHelpers([$class]);
    }

    /**
     * @param array $params
     * @return self
     */
    public function run($params = [])
    {
        $this->beforeRun($params);
        $this->bootstrap->runActions($this, $params);

        return $this;
    }

    /**
     * @param string $controller
     * @return AbstractController
     */
    public function controller($controller)
    {
        $instance = null;

        if (is_string($controller) && class_exists($controller)) {
            $instance = new $controller($this);
        }

        foreach ($this->viewHelpers as $class) {
            $instance->addViewHelper($class);
        }

        if ( ! ($instance instanceof AbstractController)) {
            throw new Exceptions\ControllerNotFoundException(
                'Controller not found or invalid'
            );
        }

        return $instance;
    }

    /**
     * @param array &$params
     * @return void
     */
    protected function beforeBootstrap()
    {
    }

    /**
     * @param array &$params
     * @return void
     */
    protected function beforeRun(&$params)
    {
    }

    /**
     * @return Timber\Timber
     */
    public function getTimber()
    {
        return $this->timber;
    }

    /**
     * @return string
     * @param string $url
     */
    public function getBaseUrl($url = '')
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');;
    }

    /**
     * @return void
     */
    public function getResourceUrl()
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($this->resourcePath, '/');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
