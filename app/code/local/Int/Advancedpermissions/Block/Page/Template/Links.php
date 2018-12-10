<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Page
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Simple links list block
 *
 * @category   Mage
 * @package    Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */

if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0'){
	
	class Int_Advancedpermissions_Block_Page_Template_Links extends Mage_Page_Block_Template_Links
	{

	}	
	
} else {

	class Int_Advancedpermissions_Block_Page_Template_Links extends Mage_Page_Block_Template_Links
	{

		/**
		 * Add link to the list
		 *
		 * @param string $label
		 * @param string $url
		 * @param string $title
		 * @param boolean $prepare
		 * @param array $urlParams
		 * @param int $position
		 * @param string|array $liParams
		 * @param string|array $aParams
		 * @param string $beforeText
		 * @param string $afterText
		 * @return Mage_Page_Block_Template_Links
		 */
		public function addLink($label, $url='', $title='', $prepare=false, $urlParams=array(),
			$position=null, $liParams=null, $aParams=null, $beforeText='', $afterText='')
		{
		
			$venderConfig = Mage::getStoreConfig('advancedpermissions/settings/displayvendorform');
			if($venderConfig == '0'){
				if($label != 'Vendor'){
					if (is_null($label) || false===$label) {
						return $this;
					}
					$link = new Varien_Object(array(
						'label'         => $label,
						'url'           => ($prepare ? $this->getUrl($url, (is_array($urlParams) ? $urlParams : array())) : $url),
						'title'         => $title,
						'li_params'     => $this->_prepareParams($liParams),
						'a_params'      => $this->_prepareParams($aParams),
						'before_text'   => $beforeText,
						'after_text'    => $afterText,
					));

					$this->_links[$this->_getNewPosition($position)] = $link;
					if (intval($position) > 0) {
						 ksort($this->_links);
					}
				}	
			} else {	
			
				if (is_null($label) || false===$label) {
					return $this;
				}
				$link = new Varien_Object(array(
					'label'         => $label,
					'url'           => ($prepare ? $this->getUrl($url, (is_array($urlParams) ? $urlParams : array())) : $url),
					'title'         => $title,
					'li_params'     => $this->_prepareParams($liParams),
					'a_params'      => $this->_prepareParams($aParams),
					'before_text'   => $beforeText,
					'after_text'    => $afterText,
				));

				$this->_links[$this->_getNewPosition($position)] = $link;
				if (intval($position) > 0) {
					 ksort($this->_links);
				}
			}	
			return $this;
		}

	}
}