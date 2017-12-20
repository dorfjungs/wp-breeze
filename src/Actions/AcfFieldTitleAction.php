<?php

namespace WpBreeze\Actions;

class AcfFieldTitleAction extends AbstractAction
{
    /**
     * {@inheritDoc}
     */
    public $name = 'acf-field-title';

    /**
     * {@inheritDoc}
     */
    public $hook = 'acf/fields/flexible_content/layout_title(10, 4)';

    /**
     * {@inheritDoc}
     */
    public $hookType = self::HOOK_FILTER;

    /**
     * @var string
     */
    protected $fieldName = 'layout_title';

    /**
     * @var string
     */
    protected $titleScheme = '%s - %s';

    /**
     * {@inheritDoc}
     */
    public function run($params = [])
    {
        list($title, $field, $layout, $i) = $params['args'];

        $newLabel = $title;

        if ($value = get_sub_field($this->fieldName)) {
            if ( ! empty($value)) {
                $newLabel = sprintf($this->titleScheme, $value, $title);
            }
        } else {
            foreach ($layout['sub_fields'] as $sub) {
                if ($sub['name'] == $this->fieldName) {
                    $key = $sub['key'];

                    if (array_key_exists($i, $field['value']) &&
                        $value = $field['value'][$i][$key]) {
                        $newLabel = sprintf($this->titleScheme, $value, $title);
                    }
                }
            }
        }

        return $newLabel;
    }
}
