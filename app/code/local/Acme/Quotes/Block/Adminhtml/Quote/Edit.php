<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote admin edit form
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Block_Adminhtml_Quote_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'acme_quotes';
        $this->_controller = 'adminhtml_quote';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('acme_quotes')->__('Save Quote')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('acme_quotes')->__('Delete Quote')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('acme_quotes')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string

     */
    public function getHeaderText()
    {
        if (Mage::registry('current_quote') && Mage::registry('current_quote')->getId()) {
            return Mage::helper('acme_quotes')->__(
                "Edit Quote '%s'",
                $this->escapeHtml(Mage::registry('current_quote')->getName())
            );
        } else {
            return Mage::helper('acme_quotes')->__('Add Testimonial');
        }
    }
}
