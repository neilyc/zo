<?php

class CP_Rfq_Model_Mysql4_Rfq extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the rfq_id refers to the key field in your database table.
        $this->_init('rfq/rfq', 'rfq_id');
    }
}