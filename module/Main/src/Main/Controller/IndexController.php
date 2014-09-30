<?php

namespace Main\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect as PaginatorDbSelectAdapter;

class IndexController extends ActionController {

    protected $table = '\Admin\Model\Post';

    public function indexAction() {
        $adapter = $this->getServiceLocator()->get('DbAdapter');
        $post = new \Admin\Model\Post();
        $schema = $post->schemaName;
        $adapter->query("SET search_path TO $schema;", 'execute');
        $sql = new \Zend\Db\Sql\Sql($adapter);                
        $select = $sql->select()->from('post')
                ->join('user', 'post.id_user = user.id', array('login'))
                ->order(array('date_post' => 'DESC'));
        $paginatorAdapter = new PaginatorDbSelectAdapter($select, $sql);
        $paginator = new Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));        
        return new ViewModel(array(
            'posts' => $paginator
        ));
    }

    public function moreAction() {
        $id = $this->params()->fromRoute('id', 0);
        if ($id > 0) {
            $post = $this->getTable($this->table)->get($id);
            return new ViewModel(array(
                'post' => $post
            ));
        }
        throw new \Exception('Post não encontrado');
    }

}
