<?php

namespace WpBreeze\Actions;

class ImageSizeAction extends AbstractAction
{
    /**
     * {@inheritDoc}
     */
    public $name = 'image-size';

    /**
     * {@inheritDoc}
     */
    public $hook = 'after_setup_theme';

    /**
     * @var array
     */
    protected $sizes = [];

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        if (array_key_exists('sizes', $params) && is_array($params['sizes'])) {
            $this->sizes = array_replace_recursive($this->sizes, $params['sizes']);
        }

        foreach ($this->sizes as $name => $config) {
            add_image_size(
                $name,
                array_key_exists('width', $config) ? $config['width'] : 0,
                array_key_exists('height', $config) ? $config['height'] : 0,
                array_key_exists('crop', $config) ? $config['crop'] : false
            );
        }
    }

    /**
     * @param string $name
     * @param integer $width
     * @param integer $height
     * @param boolean $crop
     * @return self
     */
    public function addSize($name, $width = 0, $height = 0, $crop = false)
    {
        $this->sizes[$name] = [
            'width' => $width,
            'height' => $height,
            'crop' => $crop
        ];

        return $this;
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function removeSize($name)
    {
        if (array_key_exists($name, $this->sizes)) {
            unset($this->sizes[$name]);
            return true;
        }

        return false;
    }
}
