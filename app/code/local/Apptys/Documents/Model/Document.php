<?php

class Apptys_Documents_Model_Document extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('apptys_documents/document');
    }

    public function hasFile(){
    	if($this->getFile() && $this->getFile()!=''){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function format($type){
        $className = Mage::getConfig()->getBlockClassName('apptys_documents/adminhtml_documents_edit_renderer_upload');
        
        return Mage::getSingleton($className)->render($this);
    }

    public function getResumeHtml(){
        $className = Mage::getConfig()->getBlockClassName('apptys_documents/adminhtml_documents_edit_renderer_upload');
        $renderer = Mage::getSingleton($className);
        $resume = $this->getFileName();
        return $renderer->escapeHtml($resume);
    }

	/**
	 * Customer collection to option array for Documents form
	 *
	 * @return Mage_Customer_Model_Resource_Customer_Collection
	 */
	public function customersToOptionArray()
	{
		$customerCollection = Mage::getModel('customer/customer')->getCollection()
		    ->addAttributeToSelect('entity_id')
			->addAttributeToSelect('firstname')
		    ->addAttributeToSelect('lastname')
		    ->addAttributeToSelect('email');

		$cArray = array(array('value' => '', 'label' => 'Please Select'));
		foreach ($customerCollection as $cList)
		{
			$cArray[] = array('value' => $cList->getEntityId(), 'label' => $cList->getFirstname() . " " . $cList->getLastname() . " : " . $cList->getEmail());
		}

		return $cArray;
	}

}