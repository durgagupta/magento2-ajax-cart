<?php
namespace Done\AjaxCart\Block\Product;

use Magento\Framework\Registry;

/**
 * Class Actions
 * @package Done\AjaxCart\Block\Product
 */
class Actions extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Registry $coreRegistry,
        \Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $coreRegistry;
        $this->formKey = $formKey;
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml()
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->coreRegistry->registry('current_product');
        return parent::_toHtml();
    }
    /**
     * Get form key
     *
     * @return string
     */
    public function getFormKey()
    {
        return \Zend_Json::encode($this->formKey->getFormKey());
    }
}
