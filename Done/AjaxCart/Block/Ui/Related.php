<?php
namespace Done\AjaxCart\Block\Ui;

use Done\AjaxCart\Model\Source\DisplayRelated;
use Magento\Catalog\Model\ResourceModel\Product\Link\Product\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Module\Manager as ModuleManager;
use Done\AjaxCart\Helper\Data as aHelper;
use Magento\Framework\Data\Form\FormKey as FormKey;

/**
 * Class Related
 *
 * @package Done\AjaxCart\Block\Ui
 */
class Related extends \Magento\Catalog\Block\Product\AbstractProduct
{
    const XML_PATH_UPSELL = 'ajaxcart/additional/upsell_products';

    /**
     * @var string
     */
    protected $_template = 'ui/related.phtml';

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Related products collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    private $itemCollection;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var Done\AjaxCart\Helper\Data
     */
    protected $aHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
    * @var Magento\Framework\Data\Form\FormKey
    */
    protected $_formKey;

    /**
    * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
    */
    protected $productStatus;

    /**
    * @var \Magento\Catalog\Model\Product\Visibility
    */
    protected $productVisibility;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ModuleManager $moduleManager
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ModuleManager $moduleManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Block\Product\Context $context,
        aHelper $aHelper,
        FormKey $formKey,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->moduleManager = $moduleManager;
        $this->checkoutSession = $checkoutSession;
        $this->aHelper = $aHelper;
        $this->_formKey = $formKey;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        parent::__construct($context, $data);
    }

    /**
     * Prepare related items data
     *
     * @return \Done\AjaxCart\Block\Ui\Related
     */
    private function prepareData()
    {
        $relatedType = $this->aHelper->getConfig(self::XML_PATH_UPSELL);

        if (!($product = $this->getProduct()) || !$relatedType) {
            return $this;
        }
        $this->itemCollection = $this->getNativeUpSells($product);
        return $this;
    }

    /**
     * Get native up-sells product collection
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product[]
     */
    private function getNativeUpSells($product)
    {
        /* @var $product \Magento\Catalog\Model\Product */

        $itemCollection = $product->getUpSellProductCollection()->addAttributeToSelect(
            $this->_catalogConfig->getProductAttributes()
        )->setPositionOrder()->addStoreFilter();
        $itemCollection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
        $itemCollection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        foreach ($itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $itemCollection;
    }


    /**
     * Get quote product IDs
     *
     * @return int[]
     */
    private function getQuoteProductIds()
    {
        $quoteProductIds = [];
        if ($quote = $this->checkoutSession->getQuote()) {
            foreach ($quote->getAllVisibleItems() as $quoteItem) {
                $quoteProductIds[] = $quoteItem->getProductId();
            }
        }
        return $quoteProductIds;
    }

    /**
     * Before rendering html process
     * Prepare items collection
     *
     * @return \Done\AjaxCart\Block\Ui\Related
     */
    protected function _beforeToHtml()
    {
        $this->prepareData();
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve related items collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getItems()
    {
        return $this->itemCollection;
    }

    /**
     * Get form key
     *
     * @return string
     */
    public function getFormKey()
    {
        return \Zend_Json::encode($this->_formKey->getFormKey());
    }
}
