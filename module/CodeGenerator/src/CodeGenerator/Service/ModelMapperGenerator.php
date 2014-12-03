<?php
namespace CodeGenerator\Service;

use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\MethodGenerator;

class ModelMapperGenerator extends AbstractGenerator
{

    protected $templateClassName = 'CodeGenerator\Template\ModelMapperTemplate';

    protected $modelClassName;

    public function setClassName($className)
    {
        $this->className = ucfirst(trim($className, '\\')) . 'Table';
        $this->setModelClassName($className);
    }

    /**
     *
     * @return the $modelClassName
     */
    public function getModelClassName()
    {
        return $this->modelClassName;
    }

    /**
     *
     * @param fieldtype $modelClassName
     */
    public function setModelClassName($modelClassName)
    {
        $this->modelClassName = ucfirst(trim($modelClassName, '\\'));
    }

    /**
     *
     * @return \CodeGenerator\Service\ClassGenerator
     */
    public function generate()
    {
        $this->generateProperties();
        $this->generateGetModelMethod();
        $this->generateSaveModelMethod();
        $this->generateDeleteMethod();
        $this->writeClassToFile();
    }

    protected function generateProperties()
    {
        $classGenerator = $this->getClassGenerator();
        $modelClassFullName = $this->getNamespace() . '\\' . $this->getModelClassName();
        $classGenerator->addProperty('TABLE_NAME', $this->getTableName(), PropertyGenerator::FLAG_CONSTANT);
        $classGenerator->addProperty('MODEL_CLASS_NAME', $modelClassFullName, PropertyGenerator::FLAG_CONSTANT);
    }

    protected function generateGetModelMethod()
    {
        $classGenerator = $this->getClassGenerator();
        $methodGenerator = $classGenerator->getMethod('getModel');
        $methodGenerator->setName('get' . $this->getModelClassName());
    }

    protected function generateSaveModelMethod()
    {
        $classGenerator = $this->getClassGenerator();
        $methodName = 'save' . $this->getModelClassName();
        if (! $classGenerator->hasMethod($methodName)) {
            $methodGenerator = new MethodGenerator($methodName);
            $methodGenerator->setParameter(new ParameterGenerator(lcfirst($this->getModelClassName()), $this->getModelClassName()));
            $methodGenerator->setBody($this->getSaveModeMethodBody());
            $classGenerator->addMethodFromGenerator($methodGenerator);
        }
    }

    protected function generateDeleteMethod()
    {
        $classGenerator = $this->getClassGenerator();
        $methodGenerator = $classGenerator->getMethod('deleteModel');
        $methodGenerator->setName('delete' . $this->modelClassName);
        $methodGenerator->setParameter(new ParameterGenerator('id'));
    }

    protected function getSaveModeMethodBody()
    {
        return '$tableGateway = $this->getTableGateway();
        $data = $' . lcfirst($this->getModelClassName()) . '->toArray();
        $id = (int) $' . lcfirst($this->getModelClassName()) . '->id;
        if ($id == 0) {
            $tableGateway->insert($data);
        } else {
            if ($this->get' . $this->getModelClassName() . '($id)) {
                $tableGateway->update($data, array(
                    \'id\' => $id
                ));
            }
        }';
    }
}

