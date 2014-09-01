<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;

class UsersController extends ActionController {
    
    protected $table = '\Admin\Model\User';

    public function indexAction() {
        return new ViewModel(array(
            'users' => $this->getTable($this->table)
                ->fetchAll(array('login' => 'ASC'))
                ->toArray(),            
                )
        );
    }
    
    public function saveAction(){
        $form = new \Admin\Form\User();
        $request = $this->getRequest();
        if($request->isPost()){
            $user = new \Admin\Model\User();
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
                $data = $form->getData();
                $data['password'] = md5($data['password']);
                unset($data['submit']);
                $user->setData($data);
                $this->getTable($this->table)->save($user);
                return $this->redirect()->toUrl('/admin/users');
            }
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if($id > 0){
            $user = $this->getTable($this->table)->get($id);
            $form->bind($user);
            $form->get('password')->setValue('');            
        }        
        return new ViewModel(array('form' => $form));
    }
    
    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if($id == 0)
            throw new \Exception('Código obrigatório');
        $this->getTable($this->table)->delete($id);
        return $this->redirect()->toUrl('/admin/users');
    }

}
