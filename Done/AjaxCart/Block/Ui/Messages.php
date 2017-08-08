<?php
namespace Done\AjaxCart\Block\Ui;

use Magento\Framework\Message\MessageInterface;
use Done\AjaxCart\Helper\Data as aHelper;
/**
 * Class Messages
 * @package Done\AjaxCart\Block\Ui
 */
class Messages extends \Magento\Framework\View\Element\Template
{
    const XML_PATH_MESSAGE = 'ajaxcart/additional/message';

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var \Magento\Framework\Message\Collection|null
     */
    private $messages = null;

    /**
     * @var string
     */
    protected $_template = 'ui/messages.phtml';

    /**
     * @var Done\AjaxCart\Helper\Data
     */
    protected $aHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        aHelper $aHelper,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->messageManager = $messageManager;
        $this->aHelper = $aHelper;
    }

    /**
     * Get messages
     *
     * @return \Magento\Framework\Message\Collection|null
     */
    private function getMessages()
    {
        if ($this->messages === null) {
            $this->messages = $this->messageManager->getMessages(true);
        }
        return $this->messages;
    }

    /**
     * Get error messages
     *
     * @return \Magento\Framework\Message\MessageInterface[]
     */
    public function getErrorMessages()
    {
        return $this->getMessages()->getItemsByType(MessageInterface::TYPE_ERROR);
    }

    /**
     * Get notice messages
     *
     * @return \Magento\Framework\Message\MessageInterface[]
     */
    public function getNoticeMessages()
    {
        return $this->getMessages()->getItemsByType(MessageInterface::TYPE_NOTICE);
    }

    /**
     * Get success messages
     *
     * @return \Magento\Framework\Message\MessageInterface[]
     */
    public function getSuccessMessages()
    {
        $product = $this->getProduct();
        foreach ($this->getMessages()->getItemsByType(MessageInterface::TYPE_SUCCESS) as $message) {
            $message->setText(__($this->aHelper->getConfig(self::XML_PATH_MESSAGE),$product->getName()));
        }
        return $this->getMessages()->getItemsByType(MessageInterface::TYPE_SUCCESS);
    }

}
