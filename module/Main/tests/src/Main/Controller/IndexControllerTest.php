<?php
namespace Main\Controller;

use Core\Test\ControllerTestCase;
use Main\Controller\IndexController;
use Admin\Model\Post;
use Admin\Model\PostCategorie;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group Controller
 */

class IndexControllerTest extends ControllerTestCase{

    /**
     *
     * @var string
     */
    protected $controllerFQDN = 'Main\Controller\IndexController';
    
    /**
     * @var string
     */
    
    protected $controllerRoute = 'main';
    
    public function test404(){
        $this->routeMatch->setParam('action', 'action-nao-existente');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testIndexAction()
    {
        $postA = $this->addPost('Titulo 1');
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertArrayHasKey('posts', $variables);
        $controllerData = $variables["posts"]->getCurrentItems()->toArray();
        $this->assertEquals($postA->title, $controllerData[0]['title']);       
    }

    public function testIndexActionPaginator()
    {
        $post = array();
        for($i=0; $i< 25; $i++) {
            $post[] = $this->addPost('Titulo '.$i);
        }
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertArrayHasKey('posts', $variables);
        $paginator = $variables["posts"];
        $this->assertEquals('Zend\Paginator\Paginator', get_class($paginator));
        $posts = $paginator->getCurrentItems()->toArray();
        $this->assertEquals(10, count($posts));     
        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('page', 3);
        $result = $this->controller->dispatch($this->request, $this->response);
        $variables = $result->getVariables();
        $controllerData = $variables["posts"]->getCurrentItems()->toArray();
        $this->assertEquals(5, count($controllerData));
    }  

        /**
     * 
     * @expectedException Exception
     * @expectedExceptionMessage Post não encontrado
     */
    
    public function testInvalidPostAction(){
        $post = $this->addPost('Meu post');
        $this->routeMatch->setParam('action', 'more');
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
    }

    public function testValidPostAction(){
        $post = $this->addPost('Meu post');
        $this->routeMatch->setParam('action', 'more');
        $this->routeMatch->setParam('id', $post->id);
        $result = $this->controller->dispatch($this->request, $this->response);
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        $this->assertArrayHasKey('post', $variables);
        $variable = $variables["post"];
        $this->assertEquals($post->id, $variable->id);
        $this->assertEquals($post->title, $variable->title);
    }

    private function addPost($title){
        $post = new \Admin\Model\Post();
        $post->id_user = $this->addUser();
        $post->title = $title;
        $post->description = 'Comece com um café';      
        $post->text = 'Todavia, o comprometimento entre as equipes acarreta um 
        processo de reformulação e modernização do sistema de formação de quadros 
        que corresponde às necessidades.Gostaria de enfatizar que o fenômeno 
        da Internet acarreta um processo 
        de reformulação e modernização das novas proposições.';
        $post->date_post = date('Y-m-d H:i:s');     
        $saved = $this->getTable('Admin\Model\Post')->save($post);
        return $saved;
    }

    private function addUser(){
        $user = new \Admin\Model\User();
        $user->login = 'zoro';
        $user->password = md5('123456');
        $user->role = 'admin';
        $saved = $this->getTable('Admin\Model\User')->save($user);
        $session = $this->serviceManager->get('Session');
        $session->offsetSet('user', $user);
        return $saved->id;
    }

}

