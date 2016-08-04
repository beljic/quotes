<?php 
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote helper
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Helper_Quote extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the quotes list page
     *
     * @access public
     * @return string

     */
    public function getQuotesUrl()
    {
        if ($listKey = Mage::getStoreConfig('acme_quotes/quote/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('acme_quotes/quote/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool

     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('acme_quotes/quote/breadcrumbs');
    }

    /**
     * check if the rss for quote is enabled
     *
     * @access public
     * @return bool

     */
    public function isRssEnabled()
    {
        return  Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('acme_quotes/quote/rss');
    }

    /**
     * get the link to the quote rss list
     *
     * @access public
     * @return string

     */
    public function getRssUrl()
    {
        return Mage::getUrl('acme_quotes/quote/rss');
    }
}
