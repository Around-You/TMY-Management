<?php
namespace CodeGenerator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use CodeGenerator\Form\ModelForm;
use Zend\Db\Metadata\Metadata;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use CodeGenerator\Service\ModelGenerator;
use CodeGenerator\Service\ModelMapperGenerator;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        return false;
    }

    public function modelAction()
    {
        $message = '';
        $form = new ModelForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $message = 'Yes';
            $this->generateModelToFile($form);
            $this->generateModelMapperToFile($form);
        }
        return array(
            'message' => $message,
            'form' => $form
        );
    }

    protected function generateModelToFile($form)
    {
        $nameSpace = $form->get('namespace')->getValue();
        $className = $form->get('model_class')->getValue();
        $tableName = $form->get('table_name')->getValue();
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');

        $gnerator = new ModelGenerator($dbAdapter, $className, $nameSpace, $tableName);
        $gnerator->generate();


    }



    protected function generateModelMapperToFile($form)
    {
        $nameSpace = $form->get('namespace')->getValue();
        $className = $form->get('model_class')->getValue();
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $tableName = $form->get('table_name')->getValue();

        $gnerator = new ModelMapperGenerator($dbAdapter, $className, $nameSpace, $tableName);
        $gnerator->generate();

    }


}