<?php

class CP_Rfq_Adminhtml_RfqController extends Mage_Adminhtml_Controller_Action
{
	const XML_PATH_EMAIL_RECIPIENT  = 'rfq/rfq/recipient_email';
	const XML_PATH_EMAIL_SENDER     = 'rfq/rfq/sender_email_identity';
	const XML_PATH_EMAIL2_TEMPLATE   = 'rfq/rfq/email2_template';

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('rfq/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('rfq/rfq')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('rfq_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('rfq/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('rfq/adminhtml_rfq_edit'))
				->_addLeft($this->getLayout()->createBlock('rfq/adminhtml_rfq_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rfq')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			$postObject = new Varien_Object();
			$postObject->setData($data);
			
			$model = Mage::getModel('rfq/rfq');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					//$model->setCreatedTime(now())
				    $model->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				//if($data['cutomer_email_status'] != 1){
					$mailTemplate = Mage::getModel('core/email_template');				
					$mailTemplate->setDesignConfig(array('area' => 'frontend'))
					->setReplyTo(self::XML_PATH_EMAIL_RECIPIENT)
					->sendTransactional(
							Mage::getStoreConfig(self::XML_PATH_EMAIL2_TEMPLATE),
							Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
							$data['email'],
							null,
							array('data' => $postObject)
					);				
					$model->setData('cutomer_email_status',1);
				//}
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('rfq')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rfq')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('rfq/rfq');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $rfqIds = $this->getRequest()->getParam('rfq');
        if(!is_array($rfqIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($rfqIds as $rfqId) {
                    $rfq = Mage::getModel('rfq/rfq')->load($rfqId);
                    $rfq->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($rfqIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $rfqIds = $this->getRequest()->getParam('rfq');
        if(!is_array($rfqIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($rfqIds as $rfqId) {
                    $rfq = Mage::getSingleton('rfq/rfq')
                        ->load($rfqId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($rfqIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'rfq.csv';
        $content    = $this->getLayout()->createBlock('rfq/adminhtml_rfq_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'rfq.xml';
        $content    = $this->getLayout()->createBlock('rfq/adminhtml_rfq_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}