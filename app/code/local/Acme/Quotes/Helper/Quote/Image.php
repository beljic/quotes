<?php 
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote image helper
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Helper_Quote_Image extends Acme_Quotes_Helper_Image_Abstract
{
    /**
     * image placeholder
     * @var string
     */
    protected $_placeholder = 'images/placeholder/quote.jpg';
    /**
     * image subdir
     * @var string
     */
    protected $_subdir      = 'quote';
}
