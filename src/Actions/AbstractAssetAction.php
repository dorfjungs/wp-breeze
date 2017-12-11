<?php

namespace WpBreeze\Actions;

abstract class AbstractAssetAction extends AbstractAction
{
    /**
     * @var string
     */
    public $version = '1.0';

    /**
     * @param array $params
     * @return array
     */
    public function prepare($params = [])
    {
        $params['styles'] = [];
        $params['scripts'] = [];

        return $params;
    }

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        if (array_key_exists('styles', $params)) {
            foreach ($params['styles'] as $handle => $config) {
                $this->enqueueStyle(
                    $handle,
                    is_string($config) ? ['src' => $config] : $config
                );
            }
        }

        if (array_key_exists('scripts', $params)) {
            foreach ($params['scripts'] as $handle => $config) {
                $this->enqueueScript(
                    $handle,
                    is_string($config) ? ['src' => $config] : $config
                );
            }
        }
    }

    /**
     * @param string $url
     * @return void
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    /**
     * @param string $src
     * @return string
     */
    private function parseSource($src)
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($src, '/');
    }

    /**
     * @param string $handle
     * @param array $config
     * @return void
     */
    private function enqueueStyle($handle, $config)
    {
        wp_enqueue_style(
            $handle,
            array_key_exists('src', $config) ? $this->parseSource($config['src']) : '',
            array_key_exists('deps', $config) ? $config['deps'] : '',
            array_key_exists('version', $config) ? $config['version'] : $this->version,
            array_key_exists('media', $config) ? $config['media'] : ''
        );
    }

    /**
     * @param string $handle
     * @param array $config
     * @return void
     */
    private function enqueueScript($handle, $config)
    {
        wp_enqueue_script(
            $handle,
            array_key_exists('src', $config) ? $this->parseSource($config['src']) : '',
            array_key_exists('deps', $config) ? $config['deps'] : '',
            array_key_exists('version', $config) ? $config['version'] : $this->version,
            array_key_exists('inFooter', $config) ? $config['inFooter'] : false
        );
    }
}
