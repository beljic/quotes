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

$quote = Mage::getModel('acme_quotes/quote');

for($i=1; $i < 6; $i++) {
    $data = array(
        'quotation' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eget convallis justo, eget finibus ex. Nam porta, velit nec consectetur porttitor, sem nisi tincidunt est, ac commodo eros dolor vitae lorem."',
        'name' => 'Testimonial ' . $i,
        'author' => '- <a href="www.buy.com">Buy.com</a>',
        'status' => 1,
        'position' => $i
    );
    $quote->setData($data);
    $quote->setStoreId(1);
    $quote->save();
}

$this->endSetup();
