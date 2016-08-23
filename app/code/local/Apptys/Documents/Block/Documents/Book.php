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
 * Customer address book block
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Apptys_Documents_Block_Documents_Book extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('apptys_documents/documents/documentspage.phtml');

        $documents = Mage::getResourceModel('apptys_documents/document_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->setOrder('file_name', 'desc')
        ;

        $this->setDocuments($documents);

        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('apptys_documents')->__('My Documents'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'apptys_documents.documents.documentspage.pager')
            ->setCollection($this->getDocuments());
        $this->setChild('pager', $pager);
        $this->getDocuments()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getFileTypeToName($typeID){
        $typeNames = Mage::getSingleton('apptys_documents/document_type')->toArray();
        return $typeNames[$typeID];
    }

    public function getFileStatusToName($status){
        $statusNames = Mage::getSingleton('apptys_documents/document_status')->toArray();
        return $statusNames[$status];
    }

    public function getAddDocumentUrl()
    {
        return $this->getUrl('documents/index/new', array('_secure'=>true));
    }

    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('documents/index/', array('_secure'=>true));
    }

    public function getDeleteUrl()
    {
        return $this->getUrl('documents/index/delete');
    }

    public function getDocumentEditUrl($document)
    {
        return $this->getUrl('documents/index/edit', array('_secure'=>true, 'id'=>$document->getId()));
    }
/*
    public function getPrimaryBillingAddress()
    {
        return $this->getCustomer()->getPrimaryBillingAddress();
    }

    public function getPrimaryShippingAddress()
    {
        return $this->getCustomer()->getPrimaryShippingAddress();
    }

    public function hasPrimaryAddress()
    {
        return $this->getPrimaryBillingAddress() || $this->getPrimaryShippingAddress();
    }

    public function getAdditionalAddresses()
    {
        $addresses = $this->getCustomer()->getAdditionalAddresses();
        return empty($addresses) ? false : $addresses;
    }

    public function getAddressHtml($address)
    {
        return $address->format('html');
        //return $address->toString($address->getHtmlFormat());
    }
*/
    public function getCustomer()
    {
        $customer = $this->getData('customer');
        if (is_null($customer)) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $this->setData('customer', $customer);
        }
        return $customer;
    }
}
