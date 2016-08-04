<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quotes module install script
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('acme_quotes/quote'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Quote ID'
    )
    ->addColumn(
        'quotation',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Quotation Text'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'image',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Image'
    )
    ->addColumn(
        'author',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Author'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Quote Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Quote Creation Time'
    ) 
    ->setComment('Quote Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('acme_quotes/quote_store'))
    ->addColumn(
        'quote_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable'  => false,
            'primary'   => true,
        ),
        'Quote ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'acme_quotes/quote_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'acme_quotes/quote_store',
            'quote_id',
            'acme_quotes/quote',
            'entity_id'
        ),
        'quote_id',
        $this->getTable('acme_quotes/quote'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'acme_quotes/quote_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Quotes To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
