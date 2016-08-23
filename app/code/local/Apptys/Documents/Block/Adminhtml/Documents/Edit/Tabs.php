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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * admin customer left menu
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Apptys_Documents_Block_Adminhtml_Documents_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('apptys_documents_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('apptys_documents')->__('Document'));
    }

    protected function _beforeToHtml()
    {
/*
        if (Mage::registry('current_customer')->getId()) {
            $this->addTab('view', array(
                'label'     => Mage::helper('customer')->__('Customer View'),
                'content'   => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_view')->toHtml(),
                'active'    => true
            ));
        }
*/
        $this->addTab('document', array(
            'label'     => Mage::helper('apptys_documents')->__('Document Information'),
            'content'   => $this->getLayout()->createBlock('apptys_documents/adminhtml_documents_edit_tabs_info')->initForm()->toHtml(),
            'active'    => Mage::registry('apptys_documents')->getId() ? false : true
        ));
        $this->addTab('upload', array(
            'label'     => Mage::helper('apptys_documents')->__('Upload Document'),
            'content'   => $this->getLayout()->createBlock('apptys_documents/adminhtml_documents_edit_tabs_upload')->initForm()->toHtml(),
            'active'    => Mage::registry('apptys_documents')->getId() ? false : true
        ));


        $this->_updateActiveTab();
        Varien_Profiler::stop('apptys_documents/tabs');
        return parent::_beforeToHtml();
    }

    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if( $tabId ) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }
}
