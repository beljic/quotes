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

$this->getConnection()->insertIgnore(
    $this->getTable('admin/permission_block'),
    array('block_name' => 'acme_quotes/quote_slider', 'is_allowed' => 1)
);

$this->endSetup();
