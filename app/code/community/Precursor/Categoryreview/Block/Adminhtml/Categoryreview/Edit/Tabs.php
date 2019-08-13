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

class Precursor_Categoryreview_Block_Adminhtml_Categoryreview_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('categoryreview_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('categoryreview')->__('Review Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('categoryreview')->__('Review Information'),
          'title'     => Mage::helper('categoryreview')->__('Review Information'),
          'content'   => $this->getLayout()->createBlock('categoryreview/adminhtml_categoryreview_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}