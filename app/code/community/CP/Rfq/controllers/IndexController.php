<?php
class CP_Rfq_IndexController extends Mage_Core_Controller_Front_Action
{
	const XML_PATH_EMAIL_RECIPIENT  = 'rfq/rfq/recipient_email';
  const XML_PATH_EMAIL_CC  		= 'rfq/rfq/recipient_cc';
	const XML_PATH_EMAIL_SENDER     = 'rfq/rfq/sender_email_identity';
	const XML_PATH_EMAIL_TEMPLATE   = 'rfq/rfq/email_template';
	const XML_PATH_ENABLED          = 'rfq/rfq/enabled';
	
	public function preDispatch()
	{
		parent::preDispatch();
	
		if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED) ) {
			$this->norouteAction();
		}
	}
	
  public function indexAction()
  {
  	$this->loadLayout();     
		$this->renderLayout();
  }
    
	public function postAction()
  {
    	
    $post = $this->getRequest()->getPost();
    Mage::getSingleton('core/session')->setRfq($post); 
         
		if ( $post ) {
    	$model = Mage::getSingleton('rfq/rfq')->setData($post);
    }
  	try {
    	$postObject = new Varien_Object();
    	$postObject->setData($post);
    
    	$error = false;
    
    	if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
    		$error = true;
    	}
    	if (!Zend_Validate::is(trim($post['phone_number']) , 'NotEmpty')) {
    	  $error = true;
    	}
      if (!Zend_Validate::is(trim($post['avg_order']) , 'NotEmpty')) {
	      $error = true;
    	}
    	if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
    		$error = true;
    	}
    	if ($error) {
    		throw new Exception();
    	}  

      try {
      	if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
      		$model->setCreatedTime(now())
      		  ->setUpdateTime(now());
      	} else {
      		$model->setUpdateTime(now());
      	}
        $model->setStoreId(Mage::app()->getStore()->getStoreId());
        $model->save();	
		
				$mailTemplate = Mage::getModel('core/email_template');
				/* @var $mailTemplate Mage_Core_Model_Email_Template */
				$mailTemplate->setDesignConfig(array('area' => 'frontend'))
  				->setReplyTo($post['email'])
          ->addBcc(explode(',',Mage::getStoreConfig(self::XML_PATH_EMAIL_CC)))
  				->sendTransactional(
  						Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
  						Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
  						Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
  						null,
  						array('data' => $postObject)
  				);
					
				if (!$mailTemplate->getSentSuccess()) {
					throw new Exception();
				}
				
					//$translate->setTranslateInline(true);
					
				Mage::getSingleton('core/session')->addSuccess('Your quotation is submitted successfully and we will be back to you as soon as possible in Email.');
                  Mage::getSingleton('core/session')->setRfq(); 
                  
				$this->_redirect('*/*/');
				return;
      } catch (Exception $e) {
				Mage::getSingleton('core/session')->addError($e->getMessage());
				Mage::getSingleton('core/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		} catch (Exception $e) {
			$translate->setTranslateInline(true);
		
			Mage::getSingleton('core/session')->addError(Mage::helper('rfq')->__('Unable to submit your request. Please, try again later').$e->getMessage());
			$this->_redirect('*/*/');
			return;
		}
	}	
	
  public function loginAction()
  {
         
    $return_url = $_POST['cur_url']; 
    $session = Mage::getSingleton('customer/session');
    if ($session->isLoggedIn()) {
        $this->_redirectUrl($return_url);
        return;
    }

    if ($this->getRequest()->isPost()) {  
      $login_data = $this->getRequest()->getPost('login');
      if (empty($login_data['username']) || empty($login_data['password'])) {
        Mage::getSingleton('core/session')->addError(Mage::helper('rfq')->__('Login and password are required.').$e->getMessage());
      } else {
        try
        {
          $session->login($login_data['username'], $login_data['password']);
                            
          $this->_redirectUrl($return_url);
          return;
        } catch (Mage_Core_Exception $e) {
          switch ($e->getCode()) {
            case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
              $message = Mage::helper('onepagecheckout')->__('Email is not confirmed. <a href="%s">Resend confirmation email.</a>', Mage::helper('customer')->getEmailConfirmationUrl($login_data['username']));
              break;
            default:
              Mage::getSingleton('core/session')->addError($message.$e->getMessage());
          }
          $result['error'] = $message;
          $session->setUsername($login_data['username']);
        }
      }
    }
    $this->_redirectUrl($return_url);
    return;
        //$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
  }
    
    
  public function refreshAction()
  {
    $formId = $this->getRequest()->getPost('formId', false);
    if ($formId) {
      $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
      $this->getLayout()->createBlock('rfq/captcha_zend')->setFormId($formId)->setIsAjax(true)->toHtml();
      $this->getResponse()->setBody(json_encode(array('imgSrc' => $captchaModel->getImgSrc())));
    }
    $this->setFlag('', self::FLAG_NO_POST_DISPATCH, true);
  }
}