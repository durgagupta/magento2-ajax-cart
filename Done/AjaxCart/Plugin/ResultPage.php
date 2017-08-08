<?php

namespace Done\AjaxCart\Plugin;

class ResultPage
{

    /**
     * @var  \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    /**
     * ResultPage constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\View\Layout $layout
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\View\Layout $layout)
    {
        $this->request = $request;
        $this->layout = $layout;
    }

    /**
     * Adding the default catalog_product_view_type_ handles as well
     * 
     * @param \Magento\Framework\View\Result\Page $subject
     * @param array $parameters
     * @param type $defaultHandle
     * @return type
     */
    public function beforeAddPageLayoutHandles(
        \Magento\Framework\View\Result\Page $subject, 
        array $parameters = [], 
        $defaultHandle = null)
    {
        if ($this->request->getFullActionName() == 'ajaxcart_catalog_product_view') {
            $arrayKeys = array_keys($parameters);
            if ((count($arrayKeys) == 3) &&
                in_array('id', $arrayKeys) &&
                in_array('sku', $arrayKeys) &&
                in_array('type', $arrayKeys)) {

                return [$parameters, 'catalog_product_view'];
            }
        } else {
            return [$parameters, $defaultHandle];
        }
    }

}
