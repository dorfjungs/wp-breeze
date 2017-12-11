<?php

namespace WpBreeze\Actions;

class ThemeSupportAction extends AbstractAction
{
    /**
     * @var string
     */
    public $name = 'theme-support';

    /**
     * @var string
     */
    public $hook = 'after_setup_theme';

    /**
     * @var array
     */
    public $supports = [
        'post-thumbnail',
        'html5' => [
            'caption',
            'comment-form',
            'comment-list',
            'gallery',
            'search-form'
        ]
    ];

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        foreach ($this->supports as $key => $values) {
            if (is_string($key)) {
                add_theme_support($key, is_array($values) ? $values : [$values]);
            } else {
                add_theme_support($values);
            }
        }
    }
}
