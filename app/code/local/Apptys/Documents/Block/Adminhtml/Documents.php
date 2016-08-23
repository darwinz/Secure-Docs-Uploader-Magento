<?php

class Apptys_Documents_Block_Adminhtml_Documents extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
    	$this->_controller = 'adminhtml_documents';
	    $this->_blockGroup = 'apptys_documents';
	    $this->_headerText = Mage::helper('apptys_documents')->__('Manage Documents');
	    //$this->_addButtonLabel = Mage::helper('apptys_documents')->__('Add Item');
	    parent::__construct();
	    //$this->_removeButton('add');
	}
}