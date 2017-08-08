<?php
namespace Done\AjaxCart\Model\Cart;

/**
 * Class AddResult
 * @package Done\AjaxCart\Model\Cart
 */
class AddResult
{
    /**
     * @var bool
     */
    private $addSuccess = false;

    /**
     * @var bool
     */
    private $saveSuccess = false;

    /**
     * Set addSuccess value
     *
     * @param bool $value
     * @return $this
     */
    public function setAddSuccess($value)
    {
        $this->addSuccess = $value;
        return $this;
    }

    /**
     * Set saveSuccess value
     *
     * @param bool $value
     * @return $this
     */
    public function setSaveSuccess($value)
    {
        $this->saveSuccess = $value;
        return $this;
    }

    /**
     * Retrieves result of adding product to cart
     *
     * @return bool
     */
    public function isSuccess()
    {
        $result = $this->addSuccess && $this->saveSuccess;
        $this->addSuccess = false;
        $this->saveSuccess = false;
        return $result;
    }
}
