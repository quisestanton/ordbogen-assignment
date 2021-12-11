<?php

abstract class Controller
{
    protected $layout = '';
    protected $page = '';
    
    public function displayPage()
    {
        $this->renderView($this->layout);
    }
    
    public function getView($name)
    {
        $smarty = new Smarty();
        $smarty->setCompileDir(__DIR__.'/../../templates_c')
                ->setCacheDir(__DIR__.'/../../cache')
                ->setTemplateDir($this->getViewsDir());
        return $smarty->createTemplate($name.'.htm');
    }
    
    public function renderView($name)
    {
        $this->getView($name)->display();
        exit;
    }
    
    public function renderJson($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
    
    public function getViewPath($name)
    {
        return $this->getViewsDir().'/'.$name.'.htm';
    }
    
    public function getViewsDir()
    {
        return __DIR__.'/../../resources/views';
    }
}