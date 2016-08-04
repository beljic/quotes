<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotesacme
 */
/**
 * Admin search model
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Model_Adminhtml_Search_Quote extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Acme_Quotes_Model_Adminhtml_Search_Quote

     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('acme_quotes/quote_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $quote) {
            $arr[] = array(
                'id'          => 'quote/1/'.$quote->getId(),
                'type'        => Mage::helper('acme_quotes')->__('Quote'),
                'name'        => $quote->getName(),
                'description' => $quote->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/quotes_quote/edit',
                    array('id'=>$quote->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
