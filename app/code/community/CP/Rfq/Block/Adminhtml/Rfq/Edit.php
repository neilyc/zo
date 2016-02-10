<?php

class CP_Rfq_Block_Adminhtml_Rfq_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'rfq';
        $this->_controller = 'adminhtml_rfq';
        
        $this->_updateButton('save', 'label', Mage::helper('rfq')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('rfq')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('rfq_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'rfq_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'rfq_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('rfq_data') && Mage::registry('rfq_data')->getId() ) {
            return Mage::helper('rfq')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('rfq_data')->getTitle()));
        } else {
            return Mage::helper('rfq')->__('Add Item');
        }
    }
}