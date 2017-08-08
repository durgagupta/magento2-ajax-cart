<?php
namespace Done\AjaxCart\Model\Renderer;

use Done\AjaxCart\Block\Ui\Messages;
use Done\AjaxCart\Block\Ui\Product\Image;

/**
 * Class AbstractRenderer
 * @package Done\AjaxCart\Model\Renderer
 */
abstract class AbstractRenderer implements \Done\AjaxCart\Model\RendererInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Append product image to block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     */
    protected function appendProductImage($block, $layout, $product)
    {
        $imageBlock = $layout->createBlock(
            Image::class,
            'ajaxcart.ui.product.image',
            ['data' => ['product' => $product]]
        );
        $block->append($imageBlock, 'product_image');
        return $this;
    }

    /**
     * Append messages to block
     *
     * @param \Magento\Framework\View\Element\Template $block
     * @param \Magento\Framework\View\Layout $layout
     * @return $this
     */
    protected function appendMessages($block, $layout, $product)
    {
        $messagesBlock = $layout->createBlock(
            Messages::class,
            'ajaxcart.ui.messages',
            ['data' => ['product' => $product]]
        );
        $block->append($messagesBlock, 'messages');
        return $this;
    }

    /**
     * Render block
     *
     * @param \Magento\Framework\View\Layout $layout
     * @return string
     */
    abstract public function render($layout);
}
