<?php
class CP_Rfq_Block_Adminhtml_Rfq extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_rfq';
    $this->_blockGroup = 'rfq';
    $this->_headerText = Mage::helper('rfq')->__('Item Manager');
    //$this->_addButtonLabel = Mage::helper('rfq')->__('Add Item');
    parent::__construct();
  }
}