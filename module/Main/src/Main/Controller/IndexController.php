<?php

namespace Main\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbSelectAdapter;

class IndexController extends ActionController {
    
    protected $table = '\Admin\Model\Post';

    public function indexAction() {
        $post = $this->getTable($this->table);
        $sql = $post->getSql();
        $select = $sql->select();
        $paginatorAdapter = new PaginatorDbSelectAdapter($select, $sql);
        $paginator = new Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        return new ViewModel(array(
                                'posts' => $paginator
                            ));
    }

    public function moreAction(){
        $id = $this->params()->fromRoute('id', 0);
        if($id > 0){
            $post = $this->getTable($this->table)->get($id);
            return new ViewModel(array(
                'post' => $post
            ));
        }
        throw new \Exception('Post não encontrado');
    }
       
}
