<?php

class Apptys_Documents_Block_Adminhtml_Documents_Edit_Renderer_Uploaded extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

    public function render(Varien_Data_Form_Element_Abstract $element) {

        $document = Mage::getModel('apptys_documents/document');

        if($this->getDocument()){
            $document = $this->getDocument();
        }
        elseif(Mage::app()->getRequest()->getParam('id')){
            $document->load(Mage::app()->getRequest()->getParam('id'));
        }

        if($document->getFilepath() && in_array(substr($document->getFilepath(),-4),Mage::getSingleton('apptys_documents/document_type')->getImageExtensionsArray())) {
            $html = '<tr>
                        <td class="label">
                            <label for="uploaded">Document Preview</label>
                        </td>
                        <td class="value">
                            <div class="block" style="width:85%; height:320px; overflow:scroll; padding:25px; text-align:center; background-color:#747474;">
                                <img src="' . Mage::helper("adminhtml")->getUrl('adminhtml/managedoc/dl', array('filehash' => $document->getFilehash())) . '" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            <label for="uploaded">Download File</label>
                        </td>
                        <td class="value">
                            <div class="uploaded_document">
                                <a href="' . Mage::helper("adminhtml")->getUrl('adminhtml/managedoc/dl/filehash/' . $document->getFilehash()) . '" target="_blank">
                                    ' . $document->getFilepath() . '
                                </a>
                            </div>
                        </td>
                    </tr>';
        } else {
            $html = '<tr>
                        <td class="label">
                            <label for="uploaded">Download File</label>
                        </td>
                        <td class="value">
                            <div class="uploaded_document">
                                <a href="' . Mage::helper("adminhtml")->getUrl('adminhtml/managedoc/dl/filehash/' . $document->getFilehash()) . '" target="_blank">
                                    ' . $document->getFilepath() . '
                                </a>
                            </div>
                        </td>
                    </tr>';
        }

        return $html;
    }
}
