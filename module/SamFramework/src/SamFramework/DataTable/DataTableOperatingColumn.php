<?php
namespace SamFramework\DataTable;

class DataTableOperatingColumn
{

    public $type = '';

    public $label = '';

    public $url = '';

    public $condition = '';

    public $colName = '';

    protected $typeMapping = array(
        'editUrl' => '编辑',
        'deleteUrl' => '删除',
        'editModal' => '编辑',
        'deleteModal' => '删除'
    )
    ;

    /**
     *
     * @param string|array $config
     *            operating url or config array
     * @param string $colName
     *            the key of item
     */
    public function build($config, $colName)
    {
        $this->colName = $colName;
        if (is_array($config)) {
            $this->type = $config['type'];
            $this->setUrl($config['url']);
            if (isset($config['label'])) {
                $this->label = $config['label'];
            } elseif (array_key_exists($this->type, $this->typeMapping)) {
                $this->label = $this->typeMapping[$this->type];
            }
            if (isset($config['condition'])) {
                $this->condition = $config['condition'];
            }
        } else {
            $this->type = $colName;
             $this->setUrl($config);
            $this->label = $this->typeMapping[$this->type];
        }
    }

    public function setUrl($url)
    {
    	$this->url = $this->perpareParamatersOfUrl($url);
    }

    protected function perpareParamatersOfUrl($url){
        $url = str_replace('<{RowId}>', "' + row.DT_RowId + '", $url);
        return $url;
    }
}

