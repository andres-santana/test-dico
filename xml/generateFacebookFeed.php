<?php
require_once '/home/cloudpanel/htdocs/dico.com.mx/app/Mage.php';
umask(0);
Mage::app();

$xml = new DOMDocument('1.0');
$xml->formatOutput = true;
$root = $xml->appendChild($xml->createElement('rss'));
$xmlns_value = "http://base.google.com/ns/1.0";
$version_value = "2.0";
$root->appendChild($xml->createAttribute('xmlns:g'))->appendChild($xml->createTextNode($xmlns_value)); 
$root->appendChild($xml->createAttribute('version'))->appendChild($xml->createTextNode($version_value)); 
$channel = $root->appendChild($xml->createElement('channel'));
$title = $channel->appendChild($xml->createElement('title','Muebles Dico'));
$link = $channel->appendChild($xml->createElement('link','https://dico.com.mx/df/'));
$description = $channel->appendChild($xml->createElement('description','Catálogo de productos 2018'));

Mage::app()->setCurrentStore('dico_df');
$productMediaConfig = Mage::getModel('catalog/product_media_config');

$_productCollection = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToSort('created_at', 'DESC')
                        ->addAttributeToFilter('status', '1')
                        ->addAttributeToSelect('*')
                        ->addWebsiteFilter(Mage::app()->getWebsite()->getId())
                        ->load();
$n=0;

foreach ($_productCollection as $_product){
if($_product->getTypeId() == "grouped"):
       $associatedProducts = $_product->getTypeInstance(true)->getAssociatedProducts($_product);
       $_hasAssociatedProducts = count($associatedProducts) > 0;
       $cats = $_product->getCategoryIds();
       $m = 0;
       $category = "";
      foreach ($cats as $key => $category_id ) {
         $_cat = Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load($category_id);
         $category .= $_cat->getName();
            if(!($category_id == end($cats))){
              $category .= " > ";             
            }
         $m++;
      }

       //echo "IDGrouped: ".$_product->getSku();
       //echo "<br>products count: ".count($associatedProducts);
       //echo "<br>";
       if($_hasAssociatedProducts):
       foreach ($associatedProducts as $singleProduct) {
         //echo '<entry>';
         $item = $channel->appendChild($xml->createElement('item'));
         if($singleProduct->getTypeId() == "configurable"){
            $conf = Mage::getModel('catalog/product_type_configurable')->setProduct($singleProduct);
                     $childProducts = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
                        foreach ($childProducts as $childs) {
                           $stockStatus = Mage::getModel('cataloginventory/stock_item') 
                              ->loadByProduct($childs)
                              ->getIsInStock();

                           if($stockStatus){$availability = "in stock";}
                           else{$availability = "out of stock";}
                           if($childs->getImage() && $childs->getImage() != 'no_selection'){
                              $image = $productMediaConfig->getMediaUrl($childs->getThumbnail());
                           }
                           else{
                              $image =$productMediaConfig->getMediaUrl($_product->getSmallImage());
                           }
                           //$eancode = Mage::getModel('catalog/product')->load($childs->getProduct()->getId())->geteancode();
                           $item->appendChild($xml->createElement('g:id',$childs->getSku()));
                           $item->appendChild($xml->createElement('g:title',ucwords(strtolower($childs->getName()))));
                           $item->appendChild($xml->createElement('g:description',html_entity_decode($childs->getDescription())));
                           $item->appendChild($xml->createElement('g:link',$_product->getProductUrl()));
                           $item->appendChild($xml->createElement('g:image_link',$image));
                           $item->appendChild($xml->createElement('g:brand','Muebles Dico'));
                           $item->appendChild($xml->createElement('g:condition','new'));
                           $item->appendChild($xml->createElement('g:availability',$availability));
                           //$item->appendChild($xml->createElement('g:additional_image_link',$productMediaConfig->getMediaUrl($_product->getSmallImage())));
                           $item->appendChild($xml->createElement('g:price',number_format((float)$childs->getData('precio_d'),2,'.','').' MXN'));
                           $item->appendChild($xml->createElement('g:sale_price',number_format((float)$childs->getPrice(),2,'.','').' MXN'));
                           $item->appendChild($xml->createElement('g:google_product_category',$category));
                           
                           
                           //$item->appendChild($xml->createElement('g:gtin',''));
                           //$item->appendChild($xml->createElement('g:mpn',''));
                           
                           //$item->appendChild($xml->createElement('g:adult','FALSE'));
                           //$item->appendChild($xml->createElement('g:product_type',$category[0]));
                           
                        }
                    }
                    else{
                        $stockStatus = Mage::getModel('cataloginventory/stock_item') 
                              ->loadByProduct($singleProduct)
                              ->getIsInStock();

                        if($stockStatus){$availability = "in stock";}
                        else{$availability = "out of stock";}
                        if($singleProduct->getImage() && $singleProduct->getImage() != 'no_selection'){
                              $image = $productMediaConfig->getMediaUrl($singleProduct->getThumbnail());
                           }
                           else{
                              $image =$productMediaConfig->getMediaUrl($_product->getSmallImage());
                           }
                        //$eancode = Mage::getModel('catalog/product')->load($singleProduct->getProduct()->getId())->geteancode();
                        $item->appendChild($xml->createElement('g:id',$singleProduct->getSku()));
                        $item->appendChild($xml->createElement('g:title',ucwords(strtolower($singleProduct->getName()))));
                        $item->appendChild($xml->createElement('g:description',html_entity_decode($singleProduct->getDescription())));
                        $item->appendChild($xml->createElement('g:link',$_product->getProductUrl()));
                        $item->appendChild($xml->createElement('g:image_link',$image));
                        $item->appendChild($xml->createElement('g:brand','Muebles Dico'));
                        $item->appendChild($xml->createElement('g:condition','new'));
                        $item->appendChild($xml->createElement('g:availability',$availability));
                        $item->appendChild($xml->createElement('g:price',number_format((float)$singleProduct->getData('precio_d'),2,'.','').' MXN'));
                         $item->appendChild($xml->createElement('g:sale_price',number_format((float)$singleProduct->getData('special_price'),2,'.','').' MXN'));
                        //$item->appendChild($xml->createElement('g:additional_image_link',$productMediaConfig->getMediaUrl($_product->getSmallImage())));
                        $item->appendChild($xml->createElement('g:google_product_category',$category));
                        
                       
                       
                        
                        //$item->appendChild($xml->createElement('g:gtin',''));
                        //$item->appendChild($xml->createElement('g:mpn',''));
                        
                        //$item->appendChild($xml->createElement('g:adult','FALSE'));
                        //$item->appendChild($xml->createElement('g:product_type',$category[0]));
                        

                    }
            //echo '</entry>';
         }
         endif;         
endif;
   		   
}
echo 'Escrito: ' . $xml->save("facebook_feed_df.xml") . ' bytes'; // 


?>