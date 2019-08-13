<?php
/**
  * NOTICE OF LICENSE
  *
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 3 of the License, or
  * (at your option) any later version.
  *
  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with this program.  If not, see http://opensource.org/licenses/gpl-3.0.html.
  *
  * @category   Precursor
  * @package    Precursor_Categoryreview
  * @copyright  Copyright (c) ZealousWeb (http://www.precursorweb.com/)
  * @license    http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
  * @author     Precursor <magento@precursorweb.com>
  */

class Precursor_Categoryreview_Block_Adminhtml_Categoryreview_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);	  
  	  $reviewdetail	=	Mage::registry('categoryreview_data')->getData(); 

  	  $custid	=	$reviewdetail['custid'];
  	  $customer = Mage::getModel('customer/customer')->load($custid);  

      $catid  = $reviewdetail['catid'];
  	  $category = Mage::getModel('catalog/category')->load($catid); 

  	  $caturl	= $category->getUrl();
  	  $catname	= $category->getName();
  	  $cattext	= Mage::helper('categoryreview')->__('<a href="%1$s" target="_blank">%2$s</a>',$caturl,$catname);
	  
      $fieldset = $form->addFieldset('categoryreview_form', array('legend'=>Mage::helper('categoryreview')->__('Review information')));	
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('categoryreview')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));	
		  if ($customer->getId()) {
          $customerText = Mage::helper('categoryreview')->__('%2$s %3$s <a href="mailto:%4$s">(%4$s)</a>',
          $this->getUrl('*/customer/edit', array('id' => $customer->getId(), 'active_tab'=>'review')),
          $this->htmlEscape($customer->getFirstname()),
          $this->htmlEscape($customer->getLastname()),
          $this->htmlEscape($customer->getEmail()));
      } else {
          if (is_null($form->getCustid())) {
              $customerText = Mage::helper('categoryreview')->__('Guest');
          } elseif ($form->getCustid() == 0) {
              $customerText = Mage::helper('categoryreview')->__('Administrator');
          }
      }
      $fieldset->addField('custid', 'note', array(
            'label'     => Mage::helper('categoryreview')->__('Posted By'),
            'text'      => $customerText,
      ));

      $fieldset->addField('catid', 'note', array(
            'label'     => Mage::helper('categoryreview')->__('Category Name'),
            'text'      => $cattext,
      ));

      $fieldset->addField('ratings', 'note', array(
            'label'     => Mage::helper('categoryreview')->__('Ratings'),
            'text'      => $reviewdetail['ratings'].' out of 5',
      ));
      
      $fieldset->addField('nickname', 'text', array(
          'label'     => Mage::helper('categoryreview')->__('Nickname'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'nickname',
      ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('categoryreview')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('categoryreview')->__('Not Approved'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('categoryreview')->__('Approved'),
              ),
          ),
      ));
    

      $fieldset->addField('detail', 'editor', array(
          'name'      => 'detail',
          'label'     => Mage::helper('categoryreview')->__('Detail'),
          'title'     => Mage::helper('categoryreview')->__('detail'),
          'style'     => 'width:700px; height:300px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getCategoryreviewData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getCategoryreviewData());
          Mage::getSingleton('adminhtml/session')->setCategoryreviewData(null);
      } elseif ( Mage::registry('categoryreview_data') ) {
          $form->setValues(Mage::registry('categoryreview_data')->getData());
      }
      return parent::_prepareForm();
  }
}