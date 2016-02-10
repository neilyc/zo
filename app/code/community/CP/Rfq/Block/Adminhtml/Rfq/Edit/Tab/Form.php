<?php

class CP_Rfq_Block_Adminhtml_Rfq_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('rfq_form', array('legend'=>Mage::helper('rfq')->__('Item information')));
        $categoriesArray = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSort(array('position' => 'asc', 'id' => 'asc'))
            ->addFieldToFilter('is_active', array('eq'=>'1'))
            ->load()
            ->toArray();

   
    
  
     
      $fieldset->addField('cutomer_email_status', 'hidden', array(      		
      		'name'      => 'cutomer_email_status',
      ));
      
        
      $fieldset->addField('name', 'text', array(
      		'label'     => Mage::helper('rfq')->__('Customer Name'),
      		'class'     => 'required-entry',
      		'required'  => true,
      		'name'      => 'name',
      ));
      
      $fieldset->addField('email', 'text', array(
      		'label'     => Mage::helper('rfq')->__('Customer Email'),
      		'class'     => 'required-entry',
      		'required'  => true,
      		'name'      => 'email',
      ));
      
      $fieldset->addField('status', 'select', array(
      		'label' => Mage::helper('rfq')->__('Status'),
      		'name' => 'status',
      		'values' => array(
      				array(
      						'value' => 1,
      						'label' => Mage::helper('rfq')->__('Enabled'),
      				),
      				array(
      						'value' => 2,
      						'label' => Mage::helper('rfq')->__('Disabled'),
      				),      				
      		),      		
      ));      
      

      $fieldset->addField('question', 'text', array(
      		'label'     => Mage::helper('rfq')->__('Question'),
      		'class'     => 'required-entry',
      		'required'  => true,
      		'name'      => 'question',
      ));
     
      $fieldset->addField('answer', 'editor', array(
          'name'      => 'answer',
          'label'     => Mage::helper('rfq')->__('Answer'),
          'title'     => Mage::helper('rfq')->__('Answer'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getRfqData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getRfqData());
          Mage::getSingleton('adminhtml/session')->setRfqData(null);
      } elseif ( Mage::registry('rfq_data') ) {
          $form->setValues(Mage::registry('rfq_data')->getData());
      }
      return parent::_prepareForm();
  }
}