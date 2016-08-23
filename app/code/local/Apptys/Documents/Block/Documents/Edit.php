<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Customer
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer address edit block
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Apptys_Documents_Block_Documents_Edit extends Mage_Directory_Block_Data
{
    protected $_document;
    protected $_countryCollection;
    protected $_regionCollection;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->_document = Mage::getModel('apptys_documents/document');

        // Init address object
        if ($id = $this->getRequest()->getParam('id')) {
            $this->_document->load($id);
            if ($this->_document->getCustomer_id() != Mage::getSingleton('customer/session')->getCustomerId()) {
                $this->_document->setData(array());
            }
        }

        if (!$this->_document->getId()) {
            $this->_document->setPrefix($this->getCustomer()->getPrefix())
                ->setFirstname($this->getCustomer()->getFirstname())
                ->setMiddlename($this->getCustomer()->getMiddlename())
                ->setLastname($this->getCustomer()->getLastname())
                ->setSuffix($this->getCustomer()->getSuffix());
        }

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->getTitle());
        }

        if ($postedData = Mage::getSingleton('customer/session')->getDocumentFormData(true)) {
            $this->_document->addData($postedData);
        }
    }

    /**
     * Generate name block html
     *
     * @return string
     */
    public function getNameBlockHtml()
    {
        $nameBlock = $this->getLayout()
            ->createBlock('customer/widget_name')
            ->setObject($this->getDocument());

        return $nameBlock->toHtml();
    }

    public function getTitle()
    {
        if ($title = $this->getData('title')) {
            return $title;
        }
        if ($this->getDocument()->getId()) {
            $title = Mage::helper('apptys_documents')->__('Edit Document');
        }
        else {
            $title = Mage::helper('apptys_documents')->__('Add New Document');
        }
        return $title;
    }

    public function getBackUrl()
    {
        $refererUrl = $this->getRequest()->getServer('HTTP_REFERER');
        return $refererUrl;
    }

    public function getSaveUrl()
    {
        return Mage::getUrl('documents/index/formPost', array('_secure'=>true, 'id'=>$this->getDocument()->getId()));
    }

    public function getDocument()
    {
        return $this->_document;
    }

    public function getFileTypesHtml(){
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName('file_type')
            ->setId('file_type')
            ->setTitle(Mage::helper('apptys_documents')->__('File Type'))
            ->setClass('validate-select')
            ->setValue(1)
            ->setOptions(Mage::getSingleton('apptys_documents/document_type')->toArray());
        return $select->getHtml();
    }

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function getBackButtonUrl()
    {
        return $this->getUrl('*/*');
    }

    public function getImageExtensionsArray(){
        return Mage::getSingleton('apptys_documents/document_type')->getImageExtensionsArray();
    }
}
