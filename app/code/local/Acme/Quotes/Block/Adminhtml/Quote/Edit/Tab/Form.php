<?php
/**
 * Acme_Quotes extension
 * 
 * @category       Acme
 * @package        Acme_Quotes
acme
 */
/**
 * Quote edit form tab
 *
 * @category    Acme
 * @package     Acme_Quotes
 * @author      Dejan Beljic <dbeljic@acmegroup.com>
 */
class Acme_Quotes_Block_Adminhtml_Quote_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Acme_Quotes_Block_Adminhtml_Quote_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('quote_');
        $form->setFieldNameSuffix('quote');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'quote_form',
            array('legend' => Mage::helper('acme_quotes')->__('Testimonial'))
        );
        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('acme_quotes/adminhtml_quote_helper_image')
        );
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField(
            'quotation',
            'editor',
            array(
                'label' => Mage::helper('acme_quotes')->__('Quotation Text'),
                'name'  => 'quotation',
            'config' => $wysiwygConfig,
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('acme_quotes')->__('Name'),
                'name'  => 'name',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'image',
            'image',
            array(
                'label' => Mage::helper('acme_quotes')->__('Image'),
                'name'  => 'image',

           )
        );

        $fieldset->addField(
            'author',
            'text',
            array(
                'label' => Mage::helper('acme_quotes')->__('Author'),
                'name'  => 'author',
                'required'  => true,
                'class' => 'required-entry',

           )
        );
       /* $fieldset->addField(
            'url_key',
            'text',
            array(
                'label' => Mage::helper('acme_quotes')->__('Url key'),
                'name'  => 'url_key',
                'note'  => Mage::helper('acme_quotes')->__('Relative to Website Base URL')
            )
        );
       */
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('acme_quotes')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('acme_quotes')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('acme_quotes')->__('Disabled'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'position',
            'text',
            array(
                'label' => Mage::helper('acme_quotes')->__('Position'),
                'name'  => 'position',
                'required'  => false,
            )
        );

        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_quote')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_quote')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getQuoteData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getQuoteData());
            Mage::getSingleton('adminhtml/session')->setQuoteData(null);
        } elseif (Mage::registry('current_quote')) {
            $formValues = array_merge($formValues, Mage::registry('current_quote')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
