<?php
namespace Admin\Controller\Product;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Admin\Form\Product\CategoryForm;
use Application\Model\Product\Category;
use SamFramework\Core\App;

class CategoryController extends AbstractActionController
{

    protected $categoryTable;


    public function getCategoryTable()
    {
        if (! $this->categoryTable) {
            $this->categoryTable = $this->getServiceLocator()->get('Application\Model\Product\CategoryTable');
            $this->categoryTable->currentUserId = App::getUser()->id;
        }

        return $this->categoryTable;
    }


    public function indexAction()
    {
        $user = App::getUser();
        return array();
    }

    public function getCategoriesListDataAction()
    {
        $count = $this->getCategoryTable()->getFetchAllCounts();
        $categories = $this->getCategoryTable()->fetchAll($_GET['start'], $_GET['length']);
        $listData = array(
            'draw' => $_GET['draw'] ++,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => array()
        );
        foreach ($categories as $category) {
            $listData['data'][] = array(
                'DT_RowId' => $category->id,
                'title' => $category->title,
            );
        }
        $viewModel = new JsonModel($listData);
        return $viewModel;
    }
    public function addByAjaxAction()
    {

        $form = CategoryForm::getInstance($this->getServiceLocator());
        $request = $this->getRequest();
        $id = 0;
        if ($request->isPost()) {
            $category = new Category();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $category->exchangeArray($form->getData());
                $categoryTable = $this->getCategoryTable();
                $category = $categoryTable->saveCategory($category);
                $id = $category->id;
                $this->flashMessenger()->addSuccessMessage($category->title . ' 已添加');
            }
        }
        return new JsonModel(array(
        	'id' => $id
        ));
    }
    public function addAction()
    {
        $form = CategoryForm::getInstance($this->getServiceLocator());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $category->exchangeArray($form->getData());
                $categoryTable = $this->getCategoryTable();
                $category = $categoryTable->saveCategory($category);
                $this->flashMessenger()->addSuccessMessage($category->title . ' 已添加');
                return $this->redirect()->toUrl('/admin/product/category');
            }
        }

        return array(
            'form' => $form
        );
    }

    public function editAction()
    {
        $id = (int) $this->params('id', 0);
        if (! $id) {
            return $this->redirect()->toUrl('/admin/product/category/add');
        }
        try {
            $category = $this->getCategoryTable()->getCategory($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toUrl('/admin/product/category');
        }

        $form = CategoryForm::getInstance($this->getServiceLocator());
        $form->bind($category);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $categoryTable = $this->getCategoryTable();
                $category = $categoryTable->saveCategory($category);
                $this->flashMessenger()->addSuccessMessage($category->title . ' 已编辑');
                return $this->redirect()->toUrl('/admin/product/category');
            }
        }
        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        print_r(realpath('.'));
        exit();
    }






}
