<?php

class Apptys_Documents_Adminhtml_ManagedocController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
    }

    public function indexAction()
    {
        $this->_title($this->__('Documents'))->_title($this->__('Manage Documents'));
        $this->loadLayout();
        $this->_setActiveMenu('documents');

        $block = $this->getLayout()
        ->createBlock('apptys_documents/adminhtml_documents');           
        $this->_addContent($block);

        /*$block =$this->getLayout()->createBlock('apptys_documents/managedoc');
        //$this->_addContent($block);

        var_dump($block);
        //$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Catalog'), Mage::helper('adminhtml')->__('Catalog'));
        */

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('apptys_documents/adminhtml_documents_grid')->toHtml()
        );
    }

    public function exportApptysDocumentCsvAction()
    {
        $fileName = 'apptys_documents.csv';
        $grid = $this->getLayout()->createBlock('apptys_documents/adminhtml_documents_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    public function exportApptysDocumentExcelAction()
    {
        $fileName = 'apptys_documents.xml';
        $grid = $this->getLayout()->createBlock('apptys_documents/adminhtml_documents_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    public function editAction() {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('apptys_documents/document')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('apptys_documents', $model);

            $this->loadLayout();
            $this->_setActiveMenu('documents');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('apptys_documents/adminhtml_documents_edit'))
                ->_addLeft($this->getLayout()->createBlock('apptys_documents/adminhtml_documents_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('apptys_documents')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }


    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

            if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                try { 
                    // Starting upload  
                    $uploader = new Varien_File_Uploader('file');
                 
                    // Any extention would work
                    $uploader->setAllowedExtensions(array('doc','docx','pdf','jpg','jpeg','gif','bmp','png','psd','xls','xlsx','txt','csv','ppt','pptx', 'mpg', 'mpeg', 'mpv', 'mp2', 'mov', 'qt', 'avi', 'mp4', 'wmv', 'mkv', 'flv', 'm4v', '3gp')); //FORMATS
                    $uploader->setAllowRenameFiles(true);
                 
                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    // (file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);
                    $uploader->setAllowCreateFolders(true);
                   
                    // We set media as the upload dir
                    $media_path = Mage::getBaseDir('media') . DS . 'documents' . DS;
                    $filename = $_FILES['file']['name'];
                    $extension = substr(strrchr($filename, '.'), 1);
                    $filepath = md5(base64_encode(time().'_'.str_replace(' ','_',$filename))) . '.' . $extension;

                    $uploader->save($media_path, $filepath);
                } catch (Exception $e) {
        
                }
         
                //this way the name is saved in DB
                $data['file'] = $filename;
                $data['filepath'] = $filepath;
            }

            try {

                $model = Mage::getModel('apptys_documents/document');

                $docWasApproved =$model->load($this->getRequest()->getParam('id'))->getApproved()==1;
                $docGetApproved = $data['approved']==1;
                if(isset($data['remove_file']) /*$data['remove_file']*/){
                    $data['file']='';
                    $filepath = '';
                }
                $filehash = sha1(base64_encode(time().str_replace(' ','_',$filepath)));

                $model->setData($data)
                    ->setFilepath($filepath)
                    ->setFilehash($filehash)
                    ->setCustomerId($data['customer_id'])
                    ->setId($this->getRequest()->getParam('id'));

                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
                $model->save();
                if(!$docWasApproved && $docGetApproved){
                    $this->_sendEmail();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('apptys_documents')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/index', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('apptys_documents')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
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


    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('apptys_documents/document');
     
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

    private function _sendEmail(){
        $document = Mage::getModel('apptys_documents/document')->load($this->getRequest()->getParam('id'));
        $customer = Mage::getModel('customer/customer')->load($document->getCustomer_id());

        $mail = Mage::getModel('core/email');
        $mail->setToName($customer->getFirstname().' '.$customer->getLastname());
        $mail->setToEmail($customer->getEmail());
        $mail->setBody('Your document '.$document->getFilename.' was approved');
        $mail->setSubject('Document Approved');
        $mail->setFromEmail('test@email.com');
        $mail->setFromName("Magento Store");
        $mail->setType('html');
        Mage::getSingleton('core/session')->addError('Name='.$customer->getFirstname().' '.$customer->getLastname().'++'.'Email='.$customer->getEmail().'++'.'CustId='.$document->getCustomer_id().'++DocId'.$document->getFilename());
        try {
            $mail->send();
            Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
            $this->_redirect('');
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send.');
            $this->_redirect('');
        }
    }

}