<?php

namespace WpBreeze\ViewHelpers;

use WpBreeze\Exceptions\ContentNotFoundException;

class ContentViewHelper extends AbstractViewHelper
{
    /**
     * {@inheritDoc}
     */
    public $name = 'content';

    /**
     * @param string $location
     */
    public function render($location = '')
    {
        if (filter_var($location, FILTER_VALIDATE_URL)) {
            if ($this->getHttpResponseCode($location) != '200') {
                throw new ContentNotFoundException(
                    sprintf('No content found for "%s"', $location)
                );
            }
        } elseif ( ! file_exists($location)) {
            throw new ContentNotFoundException(
                sprintf('No content found for "%s"', $location)
            );
        }

        return file_get_contents($location);
    }

    /**
     * @param string $url
     * @return string
     */
    private function getHttpResponseCode($url)
    {
        $headers = get_headers($url);

        return substr($headers[0], 9, 3);
    }
}
