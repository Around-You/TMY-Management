<?php
namespace CodeGenerator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use CodeGenerator\Service\TestGenerator;

class TestController extends AbstractActionController
{

    public function indexAction()
    {

        $gnerator = new TestGenerator('','Product','Application\Model\Product\\','');
        var_dump($gnerator->testGetClass());
        return false;
    }
}