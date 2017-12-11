<?php

namespace WpBreeze\Actions;

use AcfBreeze\AcfBreeze;
use WpBreeze\Exceptions\AcfBreezeNotFoundException;

class AcfBreezeAction extends AbstractAction
{
    /**
     * @var string
     */
    public $name = 'acf-breeze';

    /**
     * @var AcfBreeze
     */
    private $acfBreeze = null;

    /**
     * @var array
     */
    private $enabledPackages = [];

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        if ( ! class_exists('\AcfBreeze\AcfBreeze')) {
            throw new AcfBreezeNotFoundException('Package acfbreeze not found');
        }

        if ( ! empty($this->enabledPackages)) {
            $this->acfBreeze = new AcfBreeze($this->enabledPackages);

            // Appene packages to view parameters
            $timber = $this->application->getTimber();

            if ( ! is_null($timber)) {
                $timber->setParameter('packages', $this->acfBreeze->getPackages());
            }
        }
    }

    /**
     * @param string $name
     * @param array $config
     * @return void
     */
    public function registerPackage($name, $config, $enable = true)
    {
        AcfBreeze::register($name, $config);

        if (true === $enable) {
            $this->enablePackage($name);
        }
    }

    /**
     * @param string $name
     * @return void
     */
    public function enablePackage($name)
    {
        $this->enabledPackages[] = $name;
    }
}
