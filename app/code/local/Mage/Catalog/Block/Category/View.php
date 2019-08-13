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

class Mage_Catalog_Block_Category_View extends Mage_Core_Block_Template
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->getLayout()->createBlock('catalog/breadcrumbs');

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $category = $this->getCurrentCategory();
            if ($title = $category->getMetaTitle()) {
                $headBlock->setTitle($title);
            }
            if ($description = $category->getMetaDescription()) {
                $headBlock->setDescription($description);
            }
            if ($keywords = $category->getMetaKeywords()) {
                $headBlock->setKeywords($keywords);
            }
            if ($this->helper('catalog/category')->canUseCanonicalTag()) {
                $headBlock->addLinkRel('canonical', $category->getUrl());
            }
            /*
            want to show rss feed in the url
            */
            if ($this->IsRssCatalogEnable() && $this->IsTopCategory()) {
                $title = $this->helper('rss')->__('%s RSS Feed',$this->getCurrentCategory()->getName());
                $headBlock->addItem('rss', $this->getRssLink(), 'title="'.$title.'"');
            }
        }

        return $this;
    }

    public function IsRssCatalogEnable()
    {
        return Mage::getStoreConfig('rss/catalog/category');
    }

    public function IsTopCategory()
    {
        return $this->getCurrentCategory()->getLevel()==2;
    }

    public function getRssLink()
    {
        return Mage::getUrl('rss/catalog/category',
            array(
                'cid' => $this->getCurrentCategory()->getId(),
                'store_id' => Mage::app()->getStore()->getId()
            )
        );
    }

    public function getProductListHtml()
    {
        return $this->getChildHtml('product_list');
    }

    /**
     * Retrieve current category model object
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        if (!$this->hasData('current_category')) {
            $this->setData('current_category', Mage::registry('current_category'));
        }
        return $this->getData('current_category');
    }

    public function getCmsBlockHtml()
    {
        if (!$this->getData('cms_block_html')) {
            $html = $this->getLayout()->createBlock('cms/block')
                ->setBlockId($this->getCurrentCategory()->getLandingPage())
                ->toHtml();
            $this->setData('cms_block_html', $html);
        }
        return $this->getData('cms_block_html');
    }

    /**
     * Check if category display mode is "Products Only"
     * @return bool
     */
    public function isProductMode()
    {
        return $this->getCurrentCategory()->getDisplayMode()==Mage_Catalog_Model_Category::DM_PRODUCT;
    }

    /**
     * Check if category display mode is "Static Block and Products"
     * @return bool
     */
    public function isMixedMode()
    {
        return $this->getCurrentCategory()->getDisplayMode()==Mage_Catalog_Model_Category::DM_MIXED;
    }

    /**
     * Check if category display mode is "Static Block Only"
     * For anchor category with applied filter Static Block Only mode not allowed
     *
     * @return bool
     */
    public function isContentMode()
    {
        $category = $this->getCurrentCategory();
        $res = false;
        if ($category->getDisplayMode()==Mage_Catalog_Model_Category::DM_PAGE) {
            $res = true;
            if ($category->getIsAnchor()) {
                $state = Mage::getSingleton('catalog/layer')->getState();
                if ($state && $state->getFilters()) {
                    $res = false;
                }
            }
        }
        return $res;
    }

    /**
     * Retrieve block cache tags based on category
     *
     * @return array
     */
    public function getCacheTags()
    {
        return array_merge(parent::getCacheTags(), $this->getCurrentCategory()->getCacheIdTags());
    }

    /**
     * Retrieve category reviews
     *
     * @return array
     */
    public function getCatreview($catid='')
    {
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('catalog_read');
        $sql    =   "SELECT * FROM `categoryreview` where catid=$catid and status!='1'";
        $catreviews = $read->fetchAll($sql);
        return $catreviews;
        /*$reviewTable = Mage::getSingleton('core/resource')->getTableName('review/catreview');*/
     
    }

    /**
     * Retrieve category average reviews
     *
     * @return array
     */
    public function getAvgCatreview($catid='')
    {
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('catalog_read');
        $sql    =   "SELECT * FROM `categoryreview` where catid=$catid and status!='1'";
        $catreviews = $read->fetchAll($sql);
        
        $product_rating = array();
        if (count($catreviews) > 0) { 
            foreach ($catreviews as $review) {
                $product_rating[] = $review['ratings'];
            }
            $avgRating = array_sum($product_rating)/count($product_rating);
        } 
        return $avgRating;
    }
}
