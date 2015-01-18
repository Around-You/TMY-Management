<?php
/**
 *
 * @author SamXiao
 *
 */
namespace SamFramework\Layout\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\InlineScript;
use Zend\View\Helper\BasePath;

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
        if (isset($options['dataTableName'])){
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
        $inlineScriptHelper->captureStart();
        echo <<<JS
$('#{$this->dataTableName}').dataTable( {
    processing: false,
    serverSide: true,
    bAutoWidth: false,
	searching: false,
	lengthChange: false,
	info: true,
	ajax: "{$this->getOptions()['getListDataUrl']}",
	columns: [
JS;
        foreach ($this->getOptions()['cols'] as $col) {
            if (isset($col['linkTarget'])) {
                switch ($col['linkTarget']) {
                    case 'editLink':
                        echo '{
                        data: "' . $col['key'] . '",
                        render: function ( data, type, row ){
    				        return "<a href=\"' . $this->getOptions()['operatingCol']['editUrl'] . '" + row.DT_RowId + "\">" + data + "</a>";
    				    } },';
                        break;
                    case 'none':
                    case false:
                        echo '{ data: "' . $col['key'] . '" },';
                        break;
                    default:
                        $linkUrl = $col['linkTarget'];
                        $linkParme = '';
                        if (isset($col['linkDataCol'])) {
                            $linkParme = '" + row.' . $col['linkDataCol'] . ' + "';
                        }
                        echo '{
                        data: "' . $col['key'] . '",
                        render: function ( data, type, row ){
    				        return "<a href=\"' . $linkUrl . $linkParme . '\">" + data + "</a>";
    				    } },';
                }
            } else {
                echo '{ data: "' . $col['key'] . '" },';
            }
        }
        if (isset($this->getOptions()['operatingCol']) && $this->getOptions()['operatingCol'] !== false) {
            echo <<<JS
        {
            data: null,
            orderable: false,
            render: function ( data, type, row ) {
                var editString = '<a href="{$this->getOptions()['operatingCol']['editUrl']}' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-pencil"></i>编辑</a>';
                var deleteString = '<a href="{$this->getOptions()['operatingCol']['deleteUrl']}' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-remove"></i>删除</a>';
                return editString + ' ' + deleteString;
            }
        }
JS;
        }
        echo ']});';
        $inlineScriptHelper->captureEnd();
    }
}

