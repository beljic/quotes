<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote resource model
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Model_Resource_Quote extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public

     */
    public function _construct()
    {
        $this->_init('acme_quotes/quote', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $quoteId
     * @return array

     */
    public function lookupStoreIds($quoteId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('acme_quotes/quote_store'), 'store_id')
            ->where('quote_id = ?', (int)$quoteId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Acme_Quotes_Model_Resource_Quote

     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Acme_Quotes_Model_Quote $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('quotes_quote_store' => $this->getTable('acme_quotes/quote_store')),
                $this->getMainTable() . '.entity_id = quotes_quote_store.quote_id',
                array()
            )
            ->where('quotes_quote_store.store_id IN (?)', $storeIds)
            ->order('quotes_quote_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Assign quote to store views
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Acme_Quotes_Model_Resource_Quote

     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('acme_quotes/quote_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'quote_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'quote_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }


    /**
     * validate before saving
     *
     * @access protected
     * @param $object
     * @return Acme_Quotes_Model_Resource_Quote

     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        return parent::_beforeSave($object);
    }
}
