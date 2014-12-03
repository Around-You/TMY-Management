<?php
namespace CodeGenerator\Service;

use CodeGenerator\Service\AbstractGenerator;

class TestGenerator extends AbstractGenerator
{
    public function testGetClass()
    {
        return $this->getFilePath();
    }
	/* (non-PHPdoc)
     * @see \CodeGenerator\Service\AbstractGenerator::generate()
     */
    public function generate()
    {
        // TODO Auto-generated method stub

    }

}

