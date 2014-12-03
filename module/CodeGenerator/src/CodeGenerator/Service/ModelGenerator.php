<?php
namespace CodeGenerator\Service;

use Zend\Code\Generator\MethodGenerator;
use Zend\Db\Metadata\Metadata;

class ModelGenerator extends AbstractGenerator
{

    protected $templateClassName = 'CodeGenerator\Template\ModelTemplate';

    protected $tableCols;

    protected $_exchangeArrayMethodTemplate = '$this-><{$colName}> = (!empty($data[\'<{$colName}>\'])) ? $data[\'<{$colName}>\'] : null;';

    protected $_toArrayMethodTemplate = '(!empty($this-><{$colName}>)) ? $data[\'<{$colName}>\'] = $this-><{$colName}> : null;';

    /**
     *
     * @return \CodeGenerator\Service\ClassGenerator
     */
    public function generate()
    {
        $this->generateUse();
        $this->generateProperties();
        $this->generateExchangeArrayMethod();
        $this->generateToArrayMethod();
        $this->writeClassToFile();
    }

    protected function generateUse()
    {
        $classGenerator = $this->getClassGenerator();
        $classGenerator->addUse('Zend\InputFilter\InputFilterAwareInterface');
        $classGenerator->addUse('Zend\InputFilter\InputFilterInterface');
        $classGenerator->setImplementedInterfaces(array(
            'InputFilterAwareInterface'
        ));
    }

    protected function generateProperties()
    {
        $classGenerator = $this->getClassGenerator();
        $cols = $this->getTabelCols();
        foreach ($cols as $col) {
            if (! $classGenerator->hasProperty($col->getName())) {
                $classGenerator->addProperty($col->getName(), '', 'public');
            }
        }
    }

    protected function generateExchangeArrayMethod()
    {
        $classGenerator = $this->getClassGenerator();
        if (! $classGenerator->hasMethod('exchangeArray')) {
            $method = new MethodGenerator();
            $cols = $this->getTabelCols();
            $methodBody = '';
            foreach ($cols as $col) {
                $methodBody .= str_replace('<{$colName}>', $col->getName(), $this->_exchangeArrayMethodTemplate) . PHP_EOL;
            }
            $method->setName('exchangeArray')
                ->setParameter('data')
                ->setBody($methodBody);
            $classGenerator->addMethodFromGenerator($method);
        }
    }

    protected function generateToArrayMethod()
    {
        $classGenerator = $this->getClassGenerator();
        if (! $classGenerator->hasMethod('toArray')) {
            $method = new MethodGenerator();
            $cols = $this->getTabelCols();
            $methodBody = '$data = array();' . PHP_EOL;
            foreach ($cols as $col) {
                $methodBody .= str_replace('<{$colName}>', $col->getName(), $this->_toArrayMethodTemplate) . PHP_EOL;
            }
            $methodBody .= 'return $data;' . PHP_EOL;
            $method->setName('toArray')->setBody($methodBody);
            $classGenerator->addMethodFromGenerator($method);
        }
    }


    protected function getTabelCols()
    {
        if (empty($this->tableCols)) {
            $metadata = new Metadata($this->dbAdapter);
            $table = $metadata->getTable($this->tableName);
            $this->tableCols = $table->getColumns();
        }
        return $this->tableCols;
    }
}

