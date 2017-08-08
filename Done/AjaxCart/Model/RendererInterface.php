<?php
namespace Done\AjaxCart\Model;

/**
 * Interface RendererInterface
 * @package Done\AjaxCart\Model
 */
interface RendererInterface
{
    /**
     * Render layout
     *
     * @param \Magento\Framework\View\Layout $layout
     * @return string
     */
    public function render($layout);
}
