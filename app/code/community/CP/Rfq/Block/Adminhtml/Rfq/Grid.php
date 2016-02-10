<?php

class CP_Rfq_Block_Adminhtml_Rfq_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('rfqGrid');
      $this->setDefaultSort('rfq_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
       $collection = Mage::getModel('rfq/rfq')->getCollection();
       $resource = Mage::getSingleton('core/resource');
    
       $this->setCollection($collection); 
       return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('rfq_id', array(
          'header'    => Mage::helper('rfq')->__('ID'),
          'align'     =>'left',
           'index'     => 'rfq_id',
      ));
	  
	  $this->addColumn('created_time', array(
          'header'    => Mage::helper('rfq')->__('Created Time'),
          'align'     =>'left',
 		  'type' => 'datetime',
          'index'     => 'created_time',
      ));
	  
	  $this->addColumn('update_time', array(
          'header'    => Mage::helper('rfq')->__('Updated Time'),
          'align'     =>'left',
 		  'type' => 'datetime',
          'index'     => 'update_time',
      ));

      $this->addColumn('name', array(
      		'header'    => Mage::helper('rfq')->__('Name'),
      		'align'     =>'left',
      		'index'     => 'name',
      ));
      $this->addColumn('email', array(
      		'header'    => Mage::helper('rfq')->__('Email'),
      		'align'     =>'left',
      		'index'     => 'email',
      ));
	  $this->addColumn('Phone', array(
      		'header'    => Mage::helper('rfq')->__('Phone'),
      		'align'     =>'left',
      		'index'     => 'phone_number',
      ));
      
      $this->addColumn('question', array(
      		'header'    => Mage::helper('rfq')->__('Question'),
      		'align'     =>'left',
      		'index'     => 'question',
      ));
	  
       $this->addColumn('answer', array(
      		'header'    => Mage::helper('rfq')->__('Answer'),
      		'align'     =>'left',
      		'index'     => 'answer',
      ));
      
	  $this->addColumn('status', array(
      		'header'    => Mage::helper('rfq')->__('Status'),
      		'align'     => 'left',
       		'index'     => 'status',
      		'type'      => 'options',
      		'options'   => array(
      				1 => 'Enabled',
      				2 => 'Disabled',
      		),
      ));

	  $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('rfq')->__('Action'),
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('rfq')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    ),
                    array(
                        'caption'   => Mage::helper('rfq')->__('Create Quote'),
                        'url'       => array('base'=> '*/*/cquote'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('rfq')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('rfq')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('rfq_id');
        $this->getMassactionBlock()->setFormFieldName('rfq');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('rfq')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('rfq')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('rfq/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('rfq')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('rfq')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}