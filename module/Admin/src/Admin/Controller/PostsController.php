<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;

class PostsController extends ActionController {
    
    protected $table = '\Admin\Model\Post';

    public function indexAction() {
        $session = $this->getService('Session');
        $user = $session->offsetGet('user');
        return new ViewModel(array(
            'posts' => $this->getTable($this->table)
                ->fetchAll(array('date_post' => 'DESC'), "id_user = ".$user->id)
                ->toArray(),            
                )
        );
    }
    
    public function saveAction(){
        $form = new \Admin\Form\Post($this->getCategories());
        $request = $this->getRequest();
        $session = $this->getService('Session');
        if($request->isPost()){
            $post = new \Admin\Model\Post();
            $postCategories = new \Admin\Model\PostCategorie();                         
            $form->setInputFilter($post->getInputFilter());                                 
            $values = $request->getPost();
            $values['date_post'] = date('Y-m-d H:i:s');
            $values['id_user'] = $session->offsetGet('user')->id;
            $form->setData($values);
            if($form->isValid()){
                $data = $form->getData();           
                $categories = $data['categories'];
                unset($data['categories']);
                unset($data['submit']);
                $post->setData($data);
                $saved = $this->getTable($this->table)->save($post);
                $this->getTable('\Admin\Model\PostCategorie')->delete($saved->id);
                foreach($categories as $categorie){
                    $postCategorie = new \Admin\Model\PostCategorie(); 
                    $postCategorie->id_post = $saved->id;
                    $postCategorie->id_categorie = $categorie;
                    $this->getTable('\Admin\Model\PostCategorie')->save($postCategorie, true);
                }
                return $this->redirect()->toUrl('/admin/posts');
            }
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if($id > 0){
            $post = $this->getTable($this->table)->get($id);
             $categories = $this->getTable('\Admin\Model\PostCategorie')->fetchAll(null, "id_post = $id", array('id_categorie'))->toArray();            
            $form->bind($post); 
            if($categories){
                $valuesCategories = null;
                foreach($categories as $categorie)
                    $valuesCategories[$categorie['id_categorie']] = $categorie['id_categorie'];
                $form->get('categories')->setValue($valuesCategories);        
            }            
        }        
        return new ViewModel(array('form' => $form));
    }
    
    public function deleteAction(){
        $id = $this->params()->fromRoute('id', 0);
        if($id == 0)
            throw new \Exception('Código obrigatório');
        $this->getTable('\Admin\Model\PostCategorie')->delete($id);        
        $this->getTable($this->table)->delete($id);
        return $this->redirect()->toUrl('/admin/posts');
    }

    private function getCategories(){
        $categories = $this->getTable('\Admin\Model\Categorie')
        ->fetchAll(array('description' => 'ASC'))->toArray();
        $result = array();
        foreach($categories as $categorie){
            $result[$categorie['id']] = $categorie['description'];
        }
        return $result;
    }

}
