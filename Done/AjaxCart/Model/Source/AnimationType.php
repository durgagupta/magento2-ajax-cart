<?php
namespace Done\AjaxCart\Model\Source;


/**
 * Class AnimationType
 * @package Done\AjaxCart\Model\Source
 */
class AnimationType implements \Magento\Framework\Option\ArrayInterface
{
    /**#@+
     * "Display Animation Types
     */
    const TYPE_POPUP = 'popup';

    const TYPE_FLYCART = 'flycart';
    /**#@-*/

    /**
     * @var null|array
     */
    private $optionArray;

    /**
     * Get option array
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            self::TYPE_POPUP => __('Popup'),
            self::TYPE_FLYCART => __('Flycart'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        if (!$this->optionArray) {
            $this->optionArray = [];
            foreach ($this->getOptions() as $value => $label) {
                $this->optionArray[] = ['value' => $value, 'label' => $label];
            }
        }
        return $this->optionArray;
    }

    /**
     * Get label by value
     *
     * @param int $value
     * @return null|\Magento\Framework\Phrase
     */
    public function getOptionLabelByValue($value)
    {
        $options = $this->getOptions();
        if (array_key_exists($value, $options)) {
            return $options[$value];
        }
        return null;
    }
}
