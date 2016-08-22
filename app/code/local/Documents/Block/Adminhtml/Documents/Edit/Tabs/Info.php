<?php
class Apptys_Documents_Block_Adminhtml_Documents_Edit_Tabs_Info extends Mage_Adminhtml_Block_Widget_Form
{


    public function __construct()
    {  
        parent::__construct();
    }

    public function initForm()
    {
    	$form = new Varien_Data_Form();
    	$model = Mage::registry('apptys_documents');

    	$fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('apptys_documents')->__('Document Information'),
            'class'     => 'fieldset-wide',
        ));

     
        if ($model->getId()) {
            $fieldset->addField('document_id', 'hidden', array(
                'name' => 'document_id',
            ));
        }

	    $fieldset->addField('customer_id', 'select', array(
		    'name'      => 'customer_id',
		    'label'     => Mage::helper('apptys_documents')->__('Customer'),
		    'required'  => true,
		    'value'     => '',
		    'values'    => Mage::getSingleton('apptys_documents/document')->customersToOptionArray()
	    ));

	    $fieldset->addField('file_name', 'text', array(
            'name'      => 'file_name',
            'label'     => Mage::helper('apptys_documents')->__('Filename'),
            'required'  => true,
        ));

        $fieldset->addField('file_type', 'select', array(
            'name'      => 'file_type',
            'label'     => Mage::helper('apptys_documents')->__('File Type'),
            'required'  => true,
            'value'      => '1',
            'values'   => Mage::getSingleton('apptys_documents/document_type')->toOptionArray(),
        ));

        ###### Document Preview section as custom rendered field ######
        if($model->getId()) {
            $customField = $fieldset->addField('uploaded', 'text', array(
                'name' => 'uploaded',
                'label' => 'Uploaded Document'
            ), 'file_type');

            $customField->setRenderer($this->getLayout()->createBlock('apptys_documents/adminhtml_documents_edit_renderer_uploaded'));
        }

        $fieldset->addField('file_description', 'textarea', array(
            'name'      => 'file_description',
            'label'     => Mage::helper('apptys_documents')->__('Description'),
            'required'  => true,
        ));

        $fieldset->addField('approved', 'select', array(
            'name'      => 'approved',
            'label'     => Mage::helper('apptys_documents')->__('Approved'),
            'required'  => true,
            'value'      => '1',
            'values'   => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $form->setValues($model->getData());

        $this->setForm($form);

        return $this;
   	}
}