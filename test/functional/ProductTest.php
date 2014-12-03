<?php
require_once 'BaseTestCase.php';

class ProductTest extends BaseTestCase
{

    public function setUpPage()
    {
        $this->url('/admin/test/removeTestData/');
        $this->login();
    }

	public function testAddAttendee()
	{
	    $this->url('/admin/product/');
	    $table = $this->byId('product-table');
	    print_r($table->text());
	    $this->assertNotContains('Kingston', $table->text());

	    $this->byLinkText('新增商品')->click();
	    $this->byLinkText('新增一个商品类别')->click();
	    $this->byId('form-field-category')->value('身体护理-测试');
        $this->byClassName('btn-modal-form-submit')->click();

        $selectedLabel = $this->select($this->byName('category_id'))->selectedLabel();
	    $this->assertEquals('身体护理-测试', $selectedLabel);

	    $this->byId('product-title')->value('贝亲（Pigeon）婴儿清洁护肤用品 IA119（礼盒装）-测试');
	    $this->byName('price')->value(158);
	    $this->byName('unit')->value('套');
	    $this->byName('description')->value('产品信息Product Information
品牌：Pigeon/贝亲
名称：贝亲清洁用品礼盒
型号：IA119
产地: 中国
产品特色Selling Point
在一个礼盒里拥有清洁、爽身、护肤三种系列。所有产品对肌肤都是低刺激性、弱酸性，100%从植物中提取原料加工而成有与皮脂组成非常相似的高保湿成分及植物性油分组成，有效滋润婴儿娇嫩的皮肤。 ');
	    $this->byName('submit')->click();

	}



}
