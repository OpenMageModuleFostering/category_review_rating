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

class Precursor_Categoryreview_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	$this->loadLayout();     
		$this->renderLayout();
    }
	
	public function catpostAction()
    {
    	$data = Mage::app()->getRequest()->getParams();
        $data['custid'] = Mage::getSingleton('customer/session')->getCustomerId();
		$data['created_at'] = date('Y-m-d H:i:s');	

		$model = Mage::getModel('categoryreview/categoryreview')->setData($data);
		$model->save();

	    $session = Mage::getSingleton('core/session');	
	    $session->addSuccess($this->__('Your review has been accepted for moderation'));
		 
        if ($redirectUrl = Mage::getSingleton('review/session')->getRedirectUrl(true)) {
            $this->_redirectUrl($redirectUrl);
            return;
        }
		$this->_redirectReferer();
    }

}