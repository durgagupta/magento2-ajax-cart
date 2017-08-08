<?php
namespace Done\AjaxCart\Model\Plugin;

use \Magento\Quote\Model\Quote as QuoteModel;

/**
 * Class Quote
 * @package Done\AjaxCart\Model\Plugin
 */
class Quote
{
    /**
     * @var \Done\AjaxCart\Model\Cart\AddResult
     */
    private $cartAddResult;

    /**
     * @param \Done\AjaxCart\Model\Cart\AddResult $cartAddResult
     */
    public function __construct(
        \Done\AjaxCart\Model\Cart\AddResult $cartAddResult
    ) {
        $this->cartAddResult = $cartAddResult;
    }

    /**
     * After addProduct() method plugin
     *
     * @param QuoteModel $quote
     * @param \Magento\Quote\Model\Quote\Item|string $result
     * @return \Magento\Quote\Model\Quote\Item|string
     */
    public function afterAddProduct($quote, $result)
    {
        $this->cartAddResult->setAddSuccess(!is_string($result));
        return $result;
    }

    /**
     * After save() method plugin
     *
     * @param QuoteModel $quote
     * @param QuoteModel $result
     * @return QuoteModel
     */
    public function afterSave($quote, $result)
    {
        $this->cartAddResult->setSaveSuccess(true);
        return $result;
    }

    /**
     * @param QuoteModel $quote
     * @param QuoteModel $result
     * @return QuoteModel
     */
    public function afterAfterSave($quote, $result)
    {
        return $this->afterSave($quote, $result);
    }
}
