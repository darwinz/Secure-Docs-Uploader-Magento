<?php

class Apptys_Documents_IndexController extends Mage_Core_Controller_Front_Action {


    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function testModelAction() {
     	$params = $this->getRequest()->getParams();
	    $document = Mage::getModel('apptys_documents/document');
	    echo("Loading the document with an ID of ".$params['id']);
	    $document->load($params['id']);
	    $data = $document->getData();
	    //var_dump($data);
    }

    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();
 
        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }    

    public function indexAction(){
	    $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Documents'));

        $block = $this->getLayout()->getBlock('customer.account.link.back');
        if ($block) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
	    $this->renderLayout();
    }

    public function editAction()
    {
        if(self::_checkOwnership($this->getRequest()->getParam('id')))
        {
            $this->_forward('form');
        }
    }

    public function newAction()
    {
        $this->_forward('form');
    }

    public function formAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('documents');
        }
        $this->renderLayout();
    }

    public function formPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/');
        }
        // Save data
        if ($this->getRequest()->isPost()) {
            $customer = $this->_getSession()->getCustomer();
            /* @var $document Mage_Customer_Model_Address */
            $document  = Mage::getModel('apptys_documents/document');

            try {

                $request = $this->getRequest();

                if($this->getRequest()->getParam('id')){
                    $document->load($this->getRequest()->getParam('id'));
                    $document->setId($this->getRequest()->getParam('id'));
                    $filename = $document->getFilepath();
                }
                $document->setCustomerId($customer->getId());
            
            	$document->setFileName($request->getPost('file_name'));
            	$document->setFileType($request->getPost('file_type'));
            	$document->setFileDescription($request->getPost('file_description'));

                $type = 'file_upload';
                if(!empty($_FILES[$type]['tmp_name'])){
                    $filename = $_FILES[$type]['name'];
                    $extension = substr(strrchr($filename, '.'), 1);
                    $filename = md5(base64_encode(time() . '_' . str_replace(' ', '_', $filename))) . '.' . $extension;
                    $filehash = sha1(base64_encode(time() . str_replace(' ', '_', $filename)));

                    if (isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '') {
                        try {
                            $uploader = new Varien_File_Uploader($type);
                            $uploader->setAllowedExtensions(array('doc', 'docx', 'pdf', 'jpg', 'jpeg', 'gif', 'bmp', 'png', 'psd', 'xls', 'xlsx', 'txt', 'csv', 'ppt', 'pptx'));
                            $uploader->setAllowRenameFiles(true);

                            $uploader->setFilesDispersion(false);
                            $uploader->setAllowCreateFolders(true);

                            $path = Mage::getBaseDir('media') . DS . 'documents' . DS;
                            $uploader->save($path, $filename);
                        } catch (Exception $e) {

                        }

                    }
                }

            	$document->setFile($filename);
            	$document->setFilepath($filename);
                $document->setFilehash($filehash);
            	$document->setApproved(0);
                $document->save();
                $this->_sendEmail();

                $this->_getSession()->addSuccess($this->__('The document has been saved.'));
                $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
                return true;

            } catch (Mage_Core_Exception $e) {
            } catch (Exception $e) {
            }
        }

        return $this->_redirectError(Mage::getUrl('*/*/edit', array('id' => $document->getId(), '_secure' => true)));
    }

    /**
     * Remove item
     */
    public function removeAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $document = Mage::getModel('apptys_documents/document')->load($id);
        if (!$document->getId()) {
            return $this->norouteAction();
        }
        try {
            $document->delete();
            //$wishlist->save();
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('customer/session')->addError(
                $this->__('An error occurred while deleting the Document: %s', $e->getMessage())
            );
        } catch (Exception $e) {
            Mage::getSingleton('customer/session')->addError(
                $this->__('An error occurred while deleting the Document: %s', $e->getMessage())
            );
        }

        //Mage::helper('wishlist')->calculate();

        $this->_redirectReferer(Mage::getUrl('*/*'));
    }

    public function deleteAction()
    {
        $documentId = $this->getRequest()->getParam('id', false);

        if ($documentId) {
            $document = Mage::getModel('customer/address')->load($documentId);

            // Validate address_id <=> customer_id
            if ($document->getCustomerId() != $this->_getSession()->getCustomerId()) {
                $this->_getSession()->addError($this->__('The address does not belong to this customer.'));
                $this->getResponse()->setRedirect(Mage::getUrl('*/*/index'));
                return;
            }

            try {
                $document->delete();
                $this->_getSession()->addSuccess($this->__('The address has been deleted.'));
            } catch (Exception $e){
                $this->_getSession()->addException($e, $this->__('An error occurred while deleting the address.'));
            }
        }
        $this->getResponse()->setRedirect(Mage::getUrl('*/*/index', array('_secure'=>true)));
    }


    private function _sendEmail(){
        $document = Mage::getModel('apptys_documents/document')->load($this->getRequest()->getParam('id'));
        $customer = $this->_getSession()->getCustomer();

        $mail = Mage::getModel('core/email');
        $mail->setToName(Mage::getStoreConfig('trans_email/ident_general/name'));
        $mail->setToEmail(Mage::getStoreConfig('trans_email/ident_general/email'));
        $mail->setBody('A new document was submitted by '.$customer->getFirstname().' '.$customer->getLastname());
        $mail->setSubject('Document to review');
        $mail->setFromEmail(Mage::getStoreConfig('trans_email/ident_general/email'));
        $mail->setFromName("Magento Store");
        $mail->setType('html');
        try {
            $mail->send();
            Mage::getSingleton('core/session')->addSuccess('A notification has been sent');
            $this->_redirect('');
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send email.');
            $this->_redirect('');
        }
    }

    public function dlAction()
    {
        $file_hash = $this->getRequest()->getParam('filehash');
        $document = Mage::getModel('apptys_documents/document')
            ->getCollection()
            ->addFieldToFilter('filehash', $file_hash)
            ->getFirstItem();

        $fullpath = Mage::getBaseDir('media') . DS . 'documents' . DS . $document->getFilepath();
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"".$document->getFilepath()."\"");
        header('Content-Length: ' . filesize($fullpath));
        readfile($fullpath);

    }
    
    public function downloadAction()
    {
        $file_hash = $this->getRequest()->getParam('filehash');
        $document = Mage::getModel('apptys_documents/document')
            ->getCollection()
            ->addFieldToFilter('filehash', $file_hash)
            ->getFirstItem();

        if(self::_checkOwnership($document->getId())){
            $fullpath = Mage::getBaseDir('media') . DS . 'documents' . DS . $document->getFilepath();
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"".$document->getFilepath()."\"");
            header('Content-Length: ' . filesize($fullpath));
            readfile($fullpath);
        }
    }

    private function _checkOwnership($document_id)
    {
        $customer = $this->_getSession()->getCustomer();
        $document = Mage::getModel('apptys_documents/document')->load($document_id);

        if ($customer->getId() != $document->getCustomerId()) {
            Mage::getSingleton('customer/session')->addError(Mage::helper('apptys_secureinbox')->__('
                    The document you are trying to access is not accessible.  If you feel this is an error, please report this issue.
            '));
            return $this->_redirect('*/*/', array('_secure'=>true));
        }

        return true;
    }
}