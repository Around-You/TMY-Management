<?php
/**
 * 本类是自动生成 datatable的助手类, 可以根据参数生成datatable的js代码
 *
 * DataTable 组件使用 jquery Datatable (http://www.datatables.net/)
 *
 * @author SamXiao
 *
 */
namespace SamFramework\Layout\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\InlineScript;
use Zend\View\Helper\BasePath;
use SamFramework\DataTable\DataTableOperatingColumn;

/**
 * 参数 Options 例子
 * $tableOptions = array(
 * 'cols' => array(
 * array(
 * 'title' => '类别名称',
 * 'key' => 'title',
 * 'linkTarget' => 'editLink' // link|editLink
 * )
 * ),
 * 'getListDataUrl' => '/category/getCategoriesListData',
 * 'operatingCol' => array(
 * 'editUrl' => '/category/edit/',
 * 'deleteUrl' => '/category/delete/',
 * ))
 */
class DataTable extends AbstractHelper
{

    protected $inlineScriptHelper;

    protected $basePathHelper;

    protected $options;

    protected $dataTableName = 'defaultDataTable';
    
    public $searching = true;

    /**
     *
     * @return the $options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    protected function getInlineScriptHelper()
    {
        if ($this->inlineScriptHelper) {
            return $this->inlineScriptHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->inlineScriptHelper = $this->view->plugin('inlinescript');
        }

        if (! $this->inlineScriptHelper instanceof InlineScript) {
            $this->inlineScriptHelper = new InlineScript();
        }

        return $this->inlineScriptHelper;
    }

    protected function getBasePathHelper()
    {
        if ($this->basePathHelper) {
            return $this->basePathHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->basePathHelper = $this->view->plugin('basepath');
        }

        if (! $this->basePathHelper instanceof BasePath) {
            $this->basePathHelper = new BasePath();
        }

        return $this->inlineScriptHelper;
    }

    /**
     * Returns site's base path, or file with base path prepended.
     *
     * $file is appended to the base path for simplicity.
     *
     * @param string|null $file
     * @throws Exception\RuntimeException
     * @return string
     */
    public function __invoke($options = array())
    {
        $this->setOptions($options);
        $html = $this->renderHtml();
        $this->renderScript();
        return $html;
    }

    protected function renderHtml()
    {
        $options = $this->getOptions();
        if (isset($options['dataTableName'])) {
            $this->dataTableName = $options['dataTableName'];
        }
        $html = '<table id="' . $this->dataTableName . '" class="table table-striped table-bordered table-hover">';
        $html .= '<thead><tr>';
        foreach ($options['cols'] as $col) {
            $html .= '<th>' . $col['title'] . '</th>';
        }
        if (isset($options['operatingCol']) && $options['operatingCol'] !== false) {
            $html .= '<th></th>';
        }
        $html .= '</tr></thead>';
        $html .= '</table>';
        return $html;
    }

    protected function renderScript()
    {
        $inlineScriptHelper = $this->getInlineScriptHelper();
        $inlineScriptHelper->appendFile($this->getView()
            ->basePath('js/dataTables/jquery.dataTables.js'));
        $inlineScriptHelper->appendFile($this->getView()
            ->basePath('js/dataTables/jquery.dataTables.bootstrap.js'));
        $option = $this->getOptions();
        $ajaxOption = array(
            'url' => $option['getListDataUrl'],
            'data' => isset($option['getListDataParams']) ? $option['getListDataParams'] : array()
        );
        $ajaxOption = json_encode($ajaxOption);
        $orderOption = isset($option['order'])?$option['order']: '[0,"desc"]';
        $searching = isset($option['searching'])?$option['searching']: 'true';
        $inlineScriptHelper->captureStart();
        echo <<<JS
var \${$this->dataTableName} = $('#{$this->dataTableName}').DataTable( {
    language: {
        search: "查询: "
    },
    processing: false,
    serverSide: true,
    bAutoWidth: false,
	searching: {$searching},
	lengthChange: false,
	info: true,
	ajax: {$ajaxOption},
    order: [{$orderOption}],
	columns: [
JS;
        echo $this->renderColumns();
        echo $this->renderOperatingCol();
        echo ']});';
        $inlineScriptHelper->captureEnd();
    }

    protected function renderColumns()
    {
        $option = $this->getOptions();
        $colHtml = '';
        foreach ($option['cols'] as $col) {
            $orderable = isset($col['orderable']) ? $col['orderable'] : true;
            if (isset($col['linkTarget'])) {
                switch ($col['linkTarget']) {
                    case 'editLink':
                        $colHtml .= '{
                        data: "' . $col['key'] . '", orderable: ' . $orderable . ',
                        render: function ( data, type, row ){
    				        return \'<a href="' . $this->perpareParamatersOfUrl($option['operatingCol']['editUrl']) . '">\' + data + "</a>";
    				    } },';
                        break;
                    case 'none':
                    case false:
                        $colHtml .= '{ data: "' . $col['key'] . '", orderable: ' . $orderable . ' },';
                        break;
                    default:
                        $linkUrl = $col['linkTarget'];
                        $linkParme = '';
                        if (isset($col['linkDataCol'])) {
                            $linkParme = '" + row.' . $col['linkDataCol'] . ' + "';
                        }
                        $colHtml .= '{
                        data: "' . $col['key'] . '", orderable: ' . $orderable . ',
                        render: function ( data, type, row ){
    				        return "<a href=\"' . $linkUrl . $linkParme . '\">" + data + "</a>";
    				    } },';
                }
            } else {
                $colHtml .= '{ data: "' . $col['key'] . '", orderable: ' . $orderable . ' },';
            }
        }
        return $colHtml;
    }

    protected function renderOperatingCol()
    {
        $option = $this->getOptions();
        $optColHtml = '';
        if (isset($option['operatingCol']) && ! empty($option['operatingCol'])) {
            $optColHtml = "{ data: null, orderable: false, render: function ( data, type, row ) {";
            $keyArray = array();
            foreach ($option['operatingCol'] as $key => $item) {

                $operatingCol = new DataTableOperatingColumn();
                $operatingCol->build($item, $key);
                $keyArray[] = $operatingCol->colName;
                if ($operatingCol->condition != '') {
                    foreach ($operatingCol->condition as $key => $value){
                        $optColHtml .= "if(row.{$key}=='{$value}'){";
                    }
                }
                switch ($operatingCol->type) {
                    case "editUrl":
//                         $href = $this->perpareParamatersOfUrl($item);
                        $optColHtml .= " var {$operatingCol->colName} = '<a href=\"{$operatingCol->url}\"> <i class=\"ace-icon glyphicon glyphicon-pencil\"></i>编辑</a>';";
                        break;
                    case "deleteUrl":
//                         $href = $this->perpareParamatersOfUrl($item);
                        $optColHtml .= " var {$operatingCol->colName} = '<a href=\"{$operatingCol->url}\"> <i class=\"ace-icon glyphicon glyphicon-remove\"></i>删除</a>';";
                        break;
                    case "editModal":
                        $optColHtml .= " var {$operatingCol->colName} = '<a href=\"{$item}\" data-id=\"' + row.DT_RowId + '\" class=\"modal-button\" data-toggle=\"modal\"> <i class=\"ace-icon glyphicon glyphicon-pencil\"></i>编辑</a>';";
                        break;
                    case "deleteModal":
                        $optColHtml .= " var {$operatingCol->colName} = '<a href=\"#modal-confirm\" data-id=\"' + row.DT_RowId + '\" class=\"modal-button\" data-toggle=\"modal\" data-url=\"{$operatingCol->url}\"> <i class=\"ace-icon glyphicon glyphicon-pencil\"></i>{$operatingCol->label}</a>';";
                        break;
                    case "confirmModal":
                        $optColHtml .= " var {$operatingCol->colName} = '<a  href=\"#{$operatingCol->modalName}\" data-id=\"' + row.DT_RowId + '\" class=\"modal-button\" data-toggle=\"modal\" data-url=\"{$operatingCol->url}\"> {$operatingCol->label}</a>';";
                        break;
                    default:
                }
                if ($operatingCol->condition != '') {
                    $optColHtml .= "}else{var {$operatingCol->colName} = ''}";
                }
            }

            $optColHtml .= " return ".implode(" + ' ' + ", $keyArray).";}}";
        }

        return $optColHtml;
    }
    protected function perpareParamatersOfUrl($url){
        $url = str_replace('<{RowId}>', "' + row.DT_RowId + '", $url);
        return $url;
    }

}

