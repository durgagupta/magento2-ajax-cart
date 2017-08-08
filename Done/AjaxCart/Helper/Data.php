<?php
namespace Done\AjaxCart\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLE = 'ajaxcart/general/enable';
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * [__construct description]
     * @param \Magento\Framework\App\Helper\Context $context[description]
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ){
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $storeManager;
    }


    /**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /*
     * return enable / disable module with magento path
     * @return string
     */
    public function isEnable()
    {
        return $this->getConfig(self::XML_PATH_ENABLE);

    }

    /*
     * return message with magento path
     * @return string
     */
    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->getStoreId());

    }

}