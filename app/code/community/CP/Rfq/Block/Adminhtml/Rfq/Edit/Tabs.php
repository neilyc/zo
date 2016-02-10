<?php

class CP_Rfq_Block_Adminhtml_Rfq_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('rfq_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('rfq')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('rfq')->__('Item Information'),
          'title'     => Mage::helper('rfq')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('rfq/adminhtml_rfq_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}