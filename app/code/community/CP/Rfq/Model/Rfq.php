<?php

class CP_Rfq_Model_Rfq extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('rfq/rfq');
    }
}