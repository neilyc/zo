<?php

class CP_Rfq_Block_Captcha extends Mage_Captcha_Block_Captcha
{
    /**
     * Renders captcha HTML (if required)
     *
     * @return string
     */
    protected function _toHtml()
    {
         
        $blockPath = 'rfq/captcha_zend';
        $block = $this->getLayout()->createBlock($blockPath);
        $block->setData($this->getData());
        return $block->toHtml();
    }
}
    
?>
