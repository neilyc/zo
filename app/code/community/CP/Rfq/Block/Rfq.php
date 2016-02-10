<?php
class CP_Rfq_Block_Rfq extends Mage_Core_Block_Template
{
	public function __construct()
    {
		parent::__construct();
	}
 
    protected function _prepareLayout()
    {
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'); 
        $breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'),'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl())); 
        $breadcrumbs->addCrumb('an_alias', array('label'=>'Rfq', 'title'=>'Rfq'));
        
         return parent::_prepareLayout();
    }
 
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
	
	
	
}
