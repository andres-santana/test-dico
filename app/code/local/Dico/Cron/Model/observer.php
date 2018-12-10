class Dico_Cron_Model_Observer
{
 
    public function ImportShippingDays()
    {
        $profileId = 8; 
		$filename = 'importarDiasEntrega.csv';
		$logFileName= $filename.'.log';  
		$recordCount = 0;

		Mage::log("Import Started",null,$logFileName);  
 
		$profile = Mage::getModel('dataflow/profile');
  
		$userModel = Mage::getModel('admin/user');
		$userModel->setUserId(0);
		Mage::getSingleton('admin/session')->setUser($userModel);
  
		if ($profileId) {
			$profile->load($profileId);
			if (!$profile->getId()) {
				Mage::getSingleton('adminhtml/session')->addError('The profile you are trying to save no longer exists');
			}
		}	

		Mage::register('current_convert_profile', $profile);

		$profile->run();
  
		$batchModel = Mage::getSingleton('dataflow/batch');
		if ($batchModel->getId()) {
		if ($batchModel->getAdapter()) {
			$batchId = $batchModel->getId(); 
			$batchImportModel = $batchModel->getBatchImportModel();
			$importIds = $batchImportModel->getIdCollection();  

			$batchModel = Mage::getModel('dataflow/batch')->load($batchId);      
			$adapter = Mage::getModel($batchModel->getAdapter());
			foreach ($importIds as $importId) {
				$recordCount++;
				try{
					$batchImportModel->load($importId);
					if (!$batchImportModel->getId()) {
						$errors[] = Mage::helper('dataflow')->__('Skip undefined row');
						continue;
					}

					$importData = $batchImportModel->getBatchData();
					try {
						$adapter->saveRow($importData);
					} catch (Exception $e) {
						Mage::log($e->getMessage(),null,$logFileName);          
						continue;
					}
        
					if ($recordCount%20 == 0) {
						Mage::log($recordCount . ' - Completed!!',null,$logFileName);
					}
					} catch(Exception $ex) {
						Mage::log('Record# ' . $recordCount . ' - SKU = ' . $importData['sku']. ' - Error - ' . $ex->getMessage(),null,$logFileName);        
					}
			}
		
			foreach ($profile->getExceptions() as $e) {
				Mage::log($e->getMessage(),null,$logFileName);          
			}
      
		}
	}
	echo 'Import Completed';
	Mage::log("Import Completed",null,$logFileName);
    return $this;
    }
}