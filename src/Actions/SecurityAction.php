<?php

namespace WpBreeze\Actions;

class SecurityAction extends AbstractAction
{
    /**
     * @var string
     */
    public $name = 'security';

    /**
     * @var string
     */
    public $hook = 'init';

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        // Remove WP Version
        remove_action('wp_head', 'wp_generator');

        // Remove Login Error Messages
        if ( ! WP_DEBUG) {
            add_filter('login_errors', function () {
                return null;
            });
        }

        // Disable author pages
        add_action('template_redirect', function () {
            if (is_author()) {
                wp_redirect(home_url());
                exit;
            }
        });
    }
}
