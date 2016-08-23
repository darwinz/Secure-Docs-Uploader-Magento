<?php
class Apptys_Documents_Block_Adminhtml_Documents_Edit_Tabs_Upload extends Mage_Adminhtml_Block_Widget_Form
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
            'legend'    => Mage::helper('apptys_documents')->__('Upload Information'),
            'class'     => 'fieldset-wide',
        ));

     
        if ($model->getId()) {
            $fieldset->addField('document_id', 'hidden', array(
                'name' => 'document_id',
            ));
        }  

        if($model->hasFile()){
            $hasFile = true;
            $fieldset->addField('file', 'link', array(
                'label'     => Mage::helper('apptys_documents')->__('File'),
                'href' => '/documents/index/download/filehash/' . $model->getFilehash(),
	            'target' => '_blank',
                'after_element_html' => '&nbsp&nbsp&nbsp&nbsp <small> *click the link to download the file </small>'
            ));
        }else{
            $fieldset->addField('file', 'file', array(
	            'name' => 'file',
                'label' => Mage::helper('apptys_documents')->__('Upload Your Document'),
                'title'     => Mage::helper('apptys_documents')->__('Upload Your Document'),
            ));
        }
        $form->setValues($model->getData());

        if($hasFile){
            $fieldset->addField('remove_file', 'checkbox', array(
              'label'     => Mage::helper('apptys_documents')->__('Remove the File?'),
              'name'      => 'remove_file',
              'checked' => false,
              'onchange' => "alert('If its checked the file will be removed while saving');",
              'after_element_html' => '&nbsp<small>*Mark to remove the file</small>',
            ));
        }

        $this->setForm($form);
        return $this;
   	}
}