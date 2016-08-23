<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->dropForeignKey(
        $installer->getTable('apptys_documents/document'),
        'FK_doc_customer_id_entity_id'
    );

$installer->getConnection()
    ->addForeignKey(
        'FK_doc_customer_id_entity_id',
        $installer->getTable('apptys_documents/document'),
        'customer_id',
        $installer->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_NO_ACTION,
        Varien_Db_Ddl_Table::ACTION_NO_ACTION
    );

$installer->endSetup();
