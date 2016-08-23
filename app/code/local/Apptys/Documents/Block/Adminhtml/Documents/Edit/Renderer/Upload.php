<?php

class Apptys_Documents_Block_Adminhtml_Documents_Edit_Renderer_Upload
extends Mage_Adminhtml_Block_Widget
{
 
	/**
	* renderer
	*
	* @param Varien_Data_Form_Element_Abstract $element
	*/
	public function render(Apptys_Documents_Model_Document $element)
	{
		$form = new Varien_Data_Form();
    	$model = $element;

    	$fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('apptys_documents')->__('Document Information'),
            'class'     => 'fieldset-wide',
        ));

     
        if ($model->getId()) {
            $fieldset->addField('document_id', 'hidden', array(
                'name' => 'document_id',
            ));
        }  
     
     
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
        return $form->getHtml();

/*
		$element->setDisabled(true);
		$disabled = true;
		$htmlId = 'use_config_' . $element->getHtmlId();
		$html = '' . $element->getLabelHtml() . '';
		$html .= 'getId() . "'. ($disabled ? ' checked="checked"' : '');
		$html .= ' onclick="toggleValueElements(this, this.parentNode);" class="checkbox" type="checkbox" />';
		$html .= ' <label for="' . $htmlId . '">' . Mage::helper('apptys_documents')->__('Do not change value') . '</label>';
		$html .= $element->getElementHtml();
		$html .= 'toggleValueElements($(\'' . $htmlId . '\'), $(\'' . $htmlId . '\').parentNode);';
		 
		return $html;*/
	}
}