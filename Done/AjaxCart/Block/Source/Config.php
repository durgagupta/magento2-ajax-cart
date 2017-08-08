<?php
namespace Done\AjaxCart\Block\Source;
use Magento\Framework\Json\Helper\Data as jsonHelper;
use Done\AjaxCart\Helper\Data as aHelper;
use Done\AjaxCart\Model\Source\AnimationType;
/**
 * Class Config
 * @package Done\AjaxCart\Block\Source
 */
class Config extends \Magento\Framework\View\Element\Template
{
    const XML_PATH_ANIMATION_TYPE = 'ajaxcart/additional/animation_type';

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var Done\AjaxCart\Helper\Data
     */
    protected $aHelper;

    /**
     * Constructor.
     * 
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        jsonHelper $jsonHelper,
        aHelper $aHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->jsonHelper = $jsonHelper;
        $this->aHelper = $aHelper;
    }
    /**
     * Get JSON-formatted options
     *
     * @return string
     */
    public function getOptions()
    {
        $_animationType = false;
        if($this->aHelper->getConfig(self::XML_PATH_ANIMATION_TYPE) == AnimationType::TYPE_POPUP){          
            $_animationType = true;
        }
        $respons = array(
            'updateCartUrl' => $this->getUrl('ajaxcart/index/updatecart'),
            'redirectCartUrl' => $this->getUrl('checkout/cart'),
            'animationType' => $_animationType
        );
        return $this->jsonHelper->jsonEncode($respons);
    }
}
