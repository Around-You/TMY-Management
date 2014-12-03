<?php
namespace CodeGenerator\Service;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Generator\FileGenerator;

abstract class AbstractGenerator
{

    protected $namespace;

    protected $className;

    protected $tableName;

    protected $filePath;

    protected $fullClassName;

    protected $templateClassName;

    protected $classGenerator;

    /**
     * TODO 重写 *
     */
    protected $dbAdapter;

    public function __construct($dbAdapter, $className, $namespace, $tableName)
    {
        $this->dbAdapter = $dbAdapter;
        $this->setNamespace($namespace);
        $this->setClassName($className);
        $this->setTableName($tableName);
    }

    abstract public function generate();

    /**
     *
     * @return the $namespace
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     *
     * @return the $className
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     *
     * @return the $tableName
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     *
     * @param fieldtype $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = trim($namespace, '\\');
    }

    /**
     *
     * @param fieldtype $className
     */
    public function setClassName($className)
    {
        $this->className = ucfirst(trim($className, '\\'));
    }

    /**
     *
     * @param fieldtype $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     *
     * @return the $fileName
     */
    public function getFilePath()
    {
        if (! $this->filePath) {
            $arrNamespace = explode('\\', $this->getNamespace());
            array_unshift($arrNamespace, 'module', $arrNamespace[0], 'src');
            array_push($arrNamespace, $this->getClassName() . '.php');
            $this->filePath = implode(DIRECTORY_SEPARATOR, $arrNamespace);
        }
        return $this->filePath;
    }

    /**
     *
     * @param fieldtype $fileName
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function getFullClassName()
    {
        if (! $this->fullClassName) {
            $this->fullClassName = $this->getNamespace() . '\\' . $this->getClassName();
        }
        return $this->fullClassName;
    }

    /**
     *
     * @return the $templateClassName
     */
    public function getTemplateClassName()
    {
        if (empty($this->templateClassName)) {
            throw new \Exception('你需要指定一个模板文件', 500);
        }
        return $this->templateClassName;
    }

    /**
     *
     * @return ClassGenerator
     */
    public function getClassGenerator()
    {
        if (! $this->classGenerator) {
            if (class_exists($this->getFullClassName())) {
                $classReflection = new ClassReflection($this->getFullClassName());
                $this->setFileName($classReflection->getFileName());
            } else {
                $classReflection = new ClassReflection($this->getTemplateClassName());
            }
            $this->classGenerator = ClassGenerator::fromReflection($classReflection);
        }
        $this->classGenerator->setName($this->getClassName());
        $this->classGenerator->setNamespaceName($this->getNamespace());
        return $this->classGenerator;
    }

    protected function writeClassToFile()
    {
        $fileGenerator = new FileGenerator();
        $fileGenerator->setClass($this->getClassGenerator());
        file_put_contents($this->getFilePath(), $fileGenerator->generate());
        chmod($this->getFilePath(), 0777);
    }
}

