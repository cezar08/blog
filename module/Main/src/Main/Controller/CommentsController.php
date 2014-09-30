<?php

namespace Main\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;

class CommentsController extends ActionController {
    
    protected $table = '\Main\Model\Comment';

    public function indexAction() {
        $id = $this->params()->fromRoute('id', 0);
        if($id == 0)
            throw new \Exception('Identificador do post é necessário');
        $form = new \Main\Form\Comment();
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            $data['date'] = date('Y-m-d H:i:s');            
            $comment = new \Main\Model\Comment();
            $form->setInputFilter($comment->getInputFilter());
            $form->setData($data);
            if($form->isValid()){
                $data = $form->getData();
                unset($data['submit']);
                $comment->setData($data);
                $this->getTable($this->table)->save($comment);
                return $this->redirect()->toUrl('/main/comments/index/id/'.$id);
            }
        }

        $form->get('id_post')->setValue($id);
        $view = new ViewModel(array(
            'comments' => $this->getTable($this->table)
                ->fetchAll(array('date' => 'ASC'), "id_post = $id")
                ->toArray(), 'form' => $form            
                )
        );
        $view->setTerminal(true);
        return $view;
    }
        
}
