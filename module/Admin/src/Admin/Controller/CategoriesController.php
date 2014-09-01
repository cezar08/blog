<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;

class CategoriesController extends ActionController {
    
    protected $table = '\Admin\Model\Categorie';

    public function indexAction() {
        return new ViewModel(array(
            'categories' => $this->getTable($this->table)
                ->fetchAll(array('description' => 'ASC'))
                ->toArray(),            
                )
        );
    }
    
    public function saveAction(){
        $form = new \Admin\Form\Categorie();
        $request = $this->getRequest();
        if($request->isPost()){
            $categorie = new \Admin\Model\Categorie();
            $form->setInputFilter($categorie->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
                $data = $form->getData();                
                unset($data['submit']);
                $categorie->setData($data);
                $this->getTable($this->table)->save($categorie);
                return $this->redirect()->toUrl('/admin/categories');
            }
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if($id > 0){
            $categorie = $this->getTable($this->table)->get($id);
            $form->bind($categorie);            
        }        
        return new ViewModel(array('form' => $form));
    }
    
    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if($id == 0)
            throw new \Exception('Código obrigatório');
        $this->getTable($this->table)->delete($id);
        return $this->redirect()->toUrl('/admin/categories');
    }

}
