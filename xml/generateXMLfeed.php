<?php
require_once '/home/cloudpanel/htdocs/dico.com.mx/app/Mage.php';
umask(0);
Mage::app();
function super_unique($array,$key)
    {
       $temp_array = [];
       foreach ($array as &$v) {
           if (!isset($temp_array[$v[$key]]))
           $temp_array[$v[$key]] =& $v;
       }
       $array = array_values($temp_array);
       return $array;

    }

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
 $productos = array();
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

       if($_hasAssociatedProducts):
       foreach ($associatedProducts as $singleProduct) {
        $item = $channel->appendChild($xml->createElement('entry'));
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
                           
                           $item->appendChild($xml->createElement('g:id',$childs->getSku()));
                           $item->appendChild($xml->createElement('g:title',$childs->getName()));
                           $item->appendChild($xml->createElement('g:description',html_entity_decode($childs->getDescription())));
                           $item->appendChild($xml->createElement('g:link',$_product->getProductUrl()));
                           $item->appendChild($xml->createElement('g:image_link',$image));
                           $item->appendChild($xml->createElement('g:brand','Muebles Dico'));
                           $item->appendChild($xml->createElement('g:adult','FALSE'));
                           $item->appendChild($xml->createElement('g:condition','new'));
                           $item->appendChild($xml->createElement('g:availability',$availability));
                           $item->appendChild($xml->createElement('g:price',number_format((float)$childs->getData('precio_d'),2,'.','').' MXN'));
                           $item->appendChild($xml->createElement('g:sale_price',number_format((float)$childs->getPrice(),2,'.','').' MXN'));
                           $item->appendChild($xml->createElement('g:google_product_category',$category));
                           $item->appendChild($xml->createElement('g:product_type',$category));
                           $item->appendChild($xml->createElement('item_group_id',$_product->getSku()));
                           
                           /*

                          $data = array('id' => $childs->getSku(),
                            'title' => ucwords(strtolower($childs->getName())),
                            'description' => html_entity_decode($childs->getDescription()),
                            'link' => $_product->getProductUrl(),
                            'image_link' => $image,
                            'brand' => 'Muebles Dico',
                            'condition' => 'new',
                            'availability' => $availability,
                            'price' => number_format((float)$childs->getData('precio_d'),2,'.','').' MXN',
                            'sale_price' => number_format((float)$childs->getPrice(),2,'.','').' MXN',
                            'google_product_category' => $category,
                            'item_group_id' => $_product->getSku()
                            );
                          array_push($productos,$data);
                          */
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
                           
                           $item->appendChild($xml->createElement('g:id',$singleProduct->getSku()));
                           $item->appendChild($xml->createElement('g:title',$singleProduct->getName()));
                           $item->appendChild($xml->createElement('g:description',html_entity_decode($singleProduct->getDescription())));
                           $item->appendChild($xml->createElement('g:link',$_product->getProductUrl()));
                           $item->appendChild($xml->createElement('g:image_link',$image));
                           $item->appendChild($xml->createElement('g:brand','Muebles Dico'));
                           $item->appendChild($xml->createElement('g:adult','FALSE'));
                           $item->appendChild($xml->createElement('g:condition','new'));
                           $item->appendChild($xml->createElement('g:availability',$availability));
                           $item->appendChild($xml->createElement('g:price',number_format((float)$singleProduct->getData('precio_d'),2,'.','').' MXN'));
                           $item->appendChild($xml->createElement('g:sale_price',number_format((float)$singleProduct->getPrice(),2,'.','').' MXN'));
                           $item->appendChild($xml->createElement('g:google_product_category',$category));
                           $item->appendChild($xml->createElement('g:product_type',$category));
                           $item->appendChild($xml->createElement('item_group_id',$_product->getSku()));

                                                   /*
                      $data = array('id' => $singleProduct->getSku(),
                            'title' => ucwords(strtolower($singleProduct->getName())),
                            'description' => html_entity_decode($singleProduct->getDescription()),
                            'link' => $_product->getProductUrl(),
                            'image_link' => $image,
                            'brand' => 'Muebles Dico',
                            'condition' => 'new',
                            'availability' => $availability,
                            'price' => number_format((float)$singleProduct->getData('precio_d'),2,'.','').' MXN',
                            'sale_price' => number_format((float)$singleProduct->getPrice(),2,'.','').' MXN',
                            'google_product_category' => $category,
                            'item_group_id' => $_product->getSku()
                            );
                      array_push($productos,$data);
                      */
                    }
         }
         endif;         
endif;
         
}
/*$productos2 = super_unique($productos,'id');

foreach ($productos2 as $product_final) {
  if (trim($product_final['title'])!='') {  
    $item = $channel->appendChild($xml->createElement('entry'));
    $item->appendChild($xml->createElement('g:id',$product_final['id']));
    $item->appendChild($xml->createElement('g:title',$product_final['title']));
    $item->appendChild($xml->createElement('g:description',$product_final['description']));
    $item->appendChild($xml->createElement('g:link',$product_final['link']));
    $item->appendChild($xml->createElement('g:image_link',$product_final['image_link']));
    $item->appendChild($xml->createElement('g:brand','Muebles Dico'));
    $item->appendChild($xml->createElement('g:adult','FALSE'));
    $item->appendChild($xml->createElement('g:condition','new'));
    $item->appendChild($xml->createElement('g:availability',$product_final['availability']));
    $item->appendChild($xml->createElement('g:price',$product_final['price']));
    $item->appendChild($xml->createElement('g:sale_price',$product_final['sale_price']));
    $item->appendChild($xml->createElement('g:google_product_category',$product_final['google_product_category']));
    $item->appendChild($xml->createElement('g:product_type',$product_final['google_product_category']));
    $item->appendChild($xml->createElement('item_group_id',$product_final['item_group_id']));
  }

}*/
echo 'Escrito: ' . $xml->save("/home/cloudpanel/htdocs/dico.com.mx/xml/criteo_df.xml") . ' bytes'; 
