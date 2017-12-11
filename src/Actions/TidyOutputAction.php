<?php

namespace WpBreeze\Actions;

class TidyOutputAction extends AbstractAction
{
    /**
     * @var string
     */
    public $name = 'tidy-output';

    /**
     * @var array
     */
    public $tidyConfig = [
        'show-body-only' => false,
        'indent' => true,
        'indent-spaces' => 4,
        'wrap' => false
    ];

    /**
     * @param array $params
     * @return void
     */
    public function prepare($params = [])
    {
        $params['tidy'] = true;
        $params['minify'] = false;

        return $params;
    }

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        $tidy = array_key_exists('tidy', $params) ? $params['tidy'] : false;
        $minify = array_key_exists('minify', $params) ? $params['minify'] : false;

        ob_start(function ($buffer) use ($tidy, $minify) {
            if (true === $tidy && ! $minify && class_exists('\tidy')) {
                $tidy = new \tidy;

                $tidy->parseString($buffer, $this->tidyConfig, 'utf8');
                $tidy->cleanRepair();

                return (string) $tidy;
            } elseif ( ! $tidy && true === $minify) {
                return preg_replace(
                    [
                        '/\>[^\S ]+/s',
                        '/[^\S ]+\</s',
                        '/(\s)+/s',
                        '/<!--(.|\s)*?-->/'
                    ],
                    [
                        '>', '<', '\\1', ''
                    ],
                    $buffer
                );
            }
            return $buffer;
        });
    }
}
