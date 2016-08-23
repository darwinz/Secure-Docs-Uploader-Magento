<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->modifyColumn($installer->getTable('apptys_documents/document'),
        'filehash',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length' => 150,
            'nullable' => false,
            'comment' => 'File Hash'
        ));

$installer
    ->getConnection()
    ->addKey(
        $installer->getTable('apptys_documents/document'),
        'IDX_UNQ_Document_Filehash',
        'filehash',
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    );

$installer->endSetup();
