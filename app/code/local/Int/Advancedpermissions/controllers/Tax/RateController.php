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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml tax rate controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
require_once('Mage/Adminhtml/controllers/Tax/RateController.php'); 
class Int_Advancedpermissions_Tax_RateController extends Mage_Adminhtml_Tax_RateController
{

    /**
     * export action from import/export tax
     *
     */
    public function exportPostAction()
    {
        /** start csv content and set template */
		if(Mage::getStoreConfig('advancedpermissions/settings/enabled') == '0')
		{
			return parent::exportPostAction();
		}
		
        $headers = new Varien_Object(array(
            'code'         => Mage::helper('tax')->__('Code'),
            'country_name' => Mage::helper('tax')->__('Country'),
            'region_name'  => Mage::helper('tax')->__('State'),
            'tax_postcode' => Mage::helper('tax')->__('Zip/Post Code'),
            'rate'         => Mage::helper('tax')->__('Rate'),
            'zip_is_range' => Mage::helper('tax')->__('Zip/Post is Range'),
            'zip_from'     => Mage::helper('tax')->__('Range From'),
            'zip_to'       => Mage::helper('tax')->__('Range To')
        ));
        $template = '"{{code}}","{{country_name}}","{{region_name}}","{{tax_postcode}}","{{rate}}"'
                . ',"{{zip_is_range}}","{{zip_from}}","{{zip_to}}"';
        $content = $headers->toString($template);

        $storeTaxTitleTemplate       = array();
        $taxCalculationRateTitleDict = array();
		
		$userModel			= Mage::getSingleton('admin/session')->getUser();	
		$userType 			= $userModel->getUsertype();
		
		if($userType != NULL){
			$userObj 			= new Int_Advancedpermissions_Block_Catalog_Product_Grid();
			$storeCollection 	= Mage::getModel('core/store')->getCollection()
											->addFieldToFilter('store_id', $userObj->getCurrentUserStoreView())
											->setLoadDefault(false);
		} else {
			$storeCollection = Mage::getModel('core/store')->getCollection()->setLoadDefault(false);
		}
		
        foreach ($storeCollection as $store) {
            $storeTitle = 'title_' . $store->getId();
            $content   .= ',"' . $store->getCode() . '"';
            $template  .= ',"{{' . $storeTitle . '}}"';
            $storeTaxTitleTemplate[$storeTitle] = null;
        }
        unset($store);

        $content .= "\n";

        foreach (Mage::getModel('tax/calculation_rate_title')->getCollection() as $title) {
            $rateId = $title->getTaxCalculationRateId();

            if (! array_key_exists($rateId, $taxCalculationRateTitleDict)) {
                $taxCalculationRateTitleDict[$rateId] = $storeTaxTitleTemplate;
            }

            $taxCalculationRateTitleDict[$rateId]['title_' . $title->getStoreId()] = $title->getValue();
        }
        unset($title);

        $collection = Mage::getResourceModel('tax/calculation_rate_collection')
            ->joinCountryTable()
            ->joinRegionTable();

        while ($rate = $collection->fetchItem()) {
            if ($rate->getTaxRegionId() == 0) {
                $rate->setRegionName('*');
            }

            if (array_key_exists($rate->getId(), $taxCalculationRateTitleDict)) {
                $rate->addData($taxCalculationRateTitleDict[$rate->getId()]);
            } else {
                $rate->addData($storeTaxTitleTemplate);
            }

            $content .= $rate->toString($template) . "\n";
        }

        $this->_prepareDownloadResponse('tax_rates.csv', $content);
    }

}
