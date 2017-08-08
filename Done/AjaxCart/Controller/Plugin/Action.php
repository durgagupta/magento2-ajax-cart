<?php
namespace Done\AjaxCart\Controller\Plugin;

use Done\AjaxCart\Model\Processor;

/**
 * Class Action
 * @package Done\AjaxCart\Controller\Plugin
 */
class Action
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param Processor $processor
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        Processor $processor
    ) {
        $this->request = $request;
        $this->processor = $processor;
    }

    /**
     * After dispatch plugin
     *
     * @param \Magento\Framework\App\Action\Action $action
     * @param \Magento\Framework\App\ResponseInterface|\Magento\Framework\View\Result\Page $response
     * @return mixed
     */
    public function afterDispatch($action, $response)
    {
        $route = implode(
            '/',
            [
                $this->request->getModuleName(),
                $this->request->getControllerName(),
                $this->request->getActionName()
            ]
        );
        $processRoutes = [
            Processor::ROUTE_ADD_TO_CART,
            Processor::ROUTE_PRODUCT_VIEW
        ];
        if (in_array($route, $processRoutes) && $this->request->getParam('ajax', false)) {
            return $this->processor->process($this->request, $response, $route);
        }
        return $response;
    }
}
