<?php
namespace Done\AjaxCart\Model;
use Done\AjaxCart\Helper\Data as aHelper;
use Done\AjaxCart\Model\Source\AnimationType as AnimationType;
/**
 * Class Processor
 * @package Done\AjaxCart\Model
 */
class Processor
{
    /**#@+
     * Routes for processing
     */
    const ROUTE_ADD_TO_CART = 'checkout/cart/add';

    const ROUTE_PRODUCT_VIEW = 'catalog/product/view';
    const XML_PATH_ANIMATION_TYPE = 'ajaxcart/additional/animation_type';
    /**#@-*/

    /**
     * @var array
     */
    private $processMethods = [
        self::ROUTE_ADD_TO_CART => 'processAddToCart',
        self::ROUTE_PRODUCT_VIEW => 'processProductView'
    ];

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var \Done\AjaxCart\Model\Cart\AddResult
     */
    private $cartAddResult;

    /**
    * @var \Magento\Store\Model\StoreManagerInterface
    */
    protected $_storeManager;

    /**
     * @var Done\AjaxCart\Helper\Data
     */
    protected $aHelper;

    /**
     * @var Done\AjaxCart\Model\Source\AnimationType
     */
    protected $animationType;

    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    private $productImageBuilder;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param Renderer $renderer
     * @param Cart\AddResult $cartAddResult
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        Renderer $renderer,
        aHelper $aHelper,
        AnimationType $animationType,
        \Done\AjaxCart\Model\Cart\AddResult $cartAddResult,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Block\Product\ImageBuilder $productImageBuilder,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->productRepository = $productRepository;
        $this->renderer = $renderer;
        $this->cartAddResult = $cartAddResult;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_storeManager=$storeManager;
        $this->aHelper = $aHelper;
        $this->productImageBuilder = $productImageBuilder;
    }

    /**
     * Run processor methods
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page $response
     * @param string $route
     * @return mixed
     */
    public function process($request, $response, $route)
    {
        if (isset($this->processMethods[$route])) {
            return call_user_func_array(
                [$this, $this->processMethods[$route]],
                [$request, $response]
            );
        }
        return $response;
    }

    /**
     * Process add to cart
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page $response
     * @return $this
     */
    private function processAddToCart($request, $response)
    {
        $_animationType = false;
        if($this->aHelper->getConfig(self::XML_PATH_ANIMATION_TYPE) == AnimationType::TYPE_POPUP){
            
            $_animationType = true;
        }
        if ($this->cartAddResult->isSuccess()) {
            $layout = $this->resultPageFactory->create()->getLayout();
            return $this->resultJsonFactory->create()->setData(
                [
                    'ui' => $this->renderer->render(
                        $layout,
                        Renderer::PART_CONFIRMATION
                    ),
                    'related' => $this->renderer->render(
                        $layout,
                        Renderer::PART_RELATED
                    ),
                    'productView' => false,
                    'animationType' => $_animationType,
                    'image' => $this->getProductImage((int)$request->getParam('product')),
                ]
            );
        }
        return $response;
    }

    /**
     * Process product view
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page $response
     * @return $this
     */
    private function processProductView($request, $response)
    {
        $_animationType = false;
        if($this->aHelper->getConfig(self::XML_PATH_ANIMATION_TYPE) == AnimationType::TYPE_POPUP){
            
            $_animationType = true;
        }

        $url = $this->_storeManager->getStore()->getBaseUrl() . 'ajaxcart/catalog_product/view/id/' . $request->getParam('id');
        return $this->resultJsonFactory->create()->setData(
            [
                'ui' => $url,
                'productView' => true,
                'animationType' => $_animationType,
                'action_url' => $request->getParam('action_url'),
                'image' => $this->getProductImage((int)$request->getParam('id')),
            ]
        );
    }

    /**
     * Get product image
     *
     * @return string
     */
    public function getProductImage($productId)
    {
        if ($product = $this->getProduct($productId)) {
            return $this->productImageBuilder->setProduct($product)
                ->setImageId('category_page_grid')
                ->create()
                ->toHtml();
        }
        return '';
    }

    /**
     * Get product
     *
     * @return \Magento\Catalog\Model\Product |bool
     */
    private function getProduct($productId)
    {
        if ($productId) {
            return $this->productRepository->getById($productId);
        }
        return false;
    }
}
