<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Form\CategoryForm;
use Application\Model\Goods\Category;
use SamFramework\Core\App;
use Application\Model\Json\DataTableResult;

class CategoryController extends AbstractActionController
{

    protected $categoryTable;

    public function getCategoryTable()
    {
        if (! $this->categoryTable) {
            $this->categoryTable = $this->getServiceLocator()->get('Application\Model\Goods\CategoryTable');
            // $this->categoryTable->currentUserId = App::getUser()->id;
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
        try {
            $where = array(
                'category.enable' => 1
            );
            $count = $this->getCategoryTable()->getFetchAllCounts($where);
            $categories = $this->getCategoryTable()->fetchAll($where, $_GET['start'], $_GET['length']);
            $returnJson = DataTableResult::buildResult($_GET['draw'], $count, $categories);
        } catch (\Exception $e) {
            $returnJson = DataTableResult::buildResult();
        }
        $viewModel = new JsonModel($returnJson->getArrayCopy());
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
                return $this->redirect()->toUrl('/category');
            }
        }

        return array(
            'form' => $form
        );
    }

    public function editAction()
    {
        $id = (int) $this->params('id', 0);
        try {
            $category = $this->getCategoryTable()->getOneById($id);
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage('该商品类型不存在，请确认后重新操作');
            return $this->redirect()->toUrl('/category');
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
                return $this->redirect()->toUrl('/category');
            }
        }
        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params('id', 0);
        $categoryTable = $this->getCategoryTable();
//         try {
            $category = $categoryTable->deleteById($id);
            $this->flashMessenger()->addSuccessMessage($category->title . ' 已禁用');
            return $this->redirect()->toUrl('/category');
//         } catch (\Exception $ex) {
//             $this->flashMessenger()->addErrorMessage('该商品类型不存在，请确认后重新操作');
//             return $this->redirect()->toUrl('/category');
//         }
    }
}
