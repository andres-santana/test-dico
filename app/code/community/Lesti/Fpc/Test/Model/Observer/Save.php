<?php
/**
 * Lesti_Fpc (http:gordonlesti.com/lestifpc)
 *
 * PHP version 5
 *
 * @link      https://github.com/GordonLesti/Lesti_Fpc
 * @package   Lesti_Fpc
 * @author    Gordon Lesti <info@gordonlesti.com>
 * @copyright Copyright (c) 2013-2014 Gordon Lesti (http://gordonlesti.com)
 * @license   http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

/**
 * Class Lesti_Fpc_Test_Model_Observer_Save
 */
class Lesti_Fpc_Test_Model_Observer_Save extends Lesti_Fpc_Test_TestCase
{
    /**
     * @test
     */
    public function testCalaogProductSaveAfter()
    {
        $this->_fpc->save('product1', 'product1_cache_id', array(sha1('product_1')));
        $this->_fpc->save('category1', 'category1_cache_id', array(sha1('category_1')));
        $this->_fpc->save('category2', 'category2_cache_id', array(sha1('category_2')));
    }
}
