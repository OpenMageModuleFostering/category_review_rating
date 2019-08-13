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

class Precursor_Categoryreview_Block_Adminhtml_Categoryreview_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('categoryreviewGrid');
      $this->setDefaultSort('categoryreview_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('categoryreview/categoryreview')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('categoryreview_id', array(
          'header'    => Mage::helper('categoryreview')->__('ID'),
          'align'     => 'right',
          'width'     => '50px',
          'index'     => 'categoryreview_id',
      ));
      $this->addColumn('title', array(
          'header'    => Mage::helper('categoryreview')->__('Title'),
          'align'     => 'left',
          'index'     => 'title',
          'width'     => '250px',
      ));
	  
      $this->addColumn('catid', array(
          'header'    => Mage::helper('categoryreview')->__('Category Name'),
          'align'     => 'left',
          'index'     => 'catid',
          'width'     => '200px',
          'renderer'  => 'Precursor_Categoryreview_Block_Adminhtml_Categoryreview_Renderer_Categoryname',
      ));
	  
      $this->addColumn('custid', array(
  			'header'    => Mage::helper('categoryreview')->__('Customer Name'),
  			'width'     => '200px',
  			'index'     => 'custid',
        'renderer'  => 'Precursor_Categoryreview_Block_Adminhtml_Categoryreview_Renderer_Customername',
      ));

      $this->addColumn('ratings', array(
        'header'    => Mage::helper('categoryreview')->__('Ratings'),
        'width'     => '50px',
        'index'     => 'ratings',
        'renderer'  => 'Precursor_Categoryreview_Block_Adminhtml_Categoryreview_Renderer_Ratings',
      )); 
      
      $this->addColumn('nickname', array(
        'header'    => Mage::helper('categoryreview')->__('Nickname'),
        'width'     => '200px',
        'index'     => 'nickname',
      )); 

      $this->addColumn('status', array(
          'header'    => Mage::helper('categoryreview')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Not Approved',
              2 => 'Approved',
          ),
      ));
	  
      $this->addColumn('action',
          array(
              'header'    =>  Mage::helper('categoryreview')->__('Action'),
              'width'     => '50',
              'type'      => 'action',
              'getter'    => 'getId',
              'actions'   => array(
                  array(
                      'caption'   => Mage::helper('categoryreview')->__('Edit'),
                      'url'       => array('base'=> '*/*/edit'),
                      'field'     => 'id'
                  )
              ),
              'filter'    => false,
              'sortable'  => false,
              'index'     => 'stores',
              'is_system' => true,
      ));
	
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('categoryreview_id');
        $this->getMassactionBlock()->setFormFieldName('categoryreview');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('categoryreview')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('categoryreview')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('categoryreview/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('categoryreview')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('categoryreview')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
}