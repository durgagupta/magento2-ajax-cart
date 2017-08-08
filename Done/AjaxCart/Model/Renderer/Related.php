<?php
namespace Done\AjaxCart\Model\Renderer;

use Done\AjaxCart\Block\Ui\Related as RelatedBlock;
use Magento\Framework\View\Element\Template;

/**
 * Class Related
 * @package Done\AjaxCart\Model\Renderer
 */
class Related extends AbstractRenderer
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($scopeConfig);
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritdoc
     */
    public function render($layout)
    {
        $relatedBlock = $layout->createBlock(
            RelatedBlock::class,
            'ajaxcart.ui.related',
            ['data' => ['product' => $this->getProduct()]]
        );
        return $relatedBlock->toHtml();
    }

    /**
     * Get product
     *
     * @return \Magento\Catalog\Model\Product |bool
     */
    private function getProduct()
    {
        $productId = (int)$this->request->getParam('product');
        if ($productId) {
            return $this->productRepository->getById($productId);
        }
        return false;
    }
}
