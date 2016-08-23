<?php

class Apptys_Documents_Block_Adminhtml_Documents_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
 		$this->_blockGroup = 'apptys_documents';
        $this->_controller = 'adminhtml_documents';
     
        parent::__construct();
     
        $this->_updateButton('save', 'label', $this->__('Save'));
        $this->_updateButton('delete', 'label', $this->__('Delete')); 


		/*$this->_addButton('save_and_continue', array(
                'label'     => Mage::helper('apptys_documents')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit(\''.$this->_getSaveAndContinueUrl().'\')',
                'class'     => 'save'
            ), 10);*/

		/*
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit(\''.$this->_getSaveAndContinueUrl().'\')',//'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);  */             
    }

    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText()
    {  
        if (Mage::registry('apptys_documents')->getId()) {
            return $this->__('Edit Document');
        }  
        else {
            return $this->__('New Document');
        }  
    } 


    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'  => true,
            'back'      => 'edit',
            'tab'       => '{{tab_id}}'
        ));
    }

}