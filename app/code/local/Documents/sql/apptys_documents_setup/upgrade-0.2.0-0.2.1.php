<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addForeignKey(
        'FK_doc_customer_id_entity_id',
        $installer->getTable('apptys_documents/document'),
        'customer_id',
        $installer->getTable('customer/entity'),
        'entity_id'
    );

$installer->endSetup();
