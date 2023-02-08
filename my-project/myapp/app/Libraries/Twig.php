<?php

namespace App\Libraries;

class Twig
{
    protected $twig;
    protected $viewPath;

    public function __construct($viewPath = null)
    {
        $this->viewPath = $viewPath;
    }

    protected function createTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->viewPath);
        $twig = new \Twig\Environment($loader, [
            'cache' => false
        ]);

        $this->twig = $twig;
    }

    public function display($view, $params = [], $functions_custom = [])
    {
        $response = service('response');
        $response->setBody($this->render($view, $params, $functions_custom));
        $response->send();
    }

    /**
     * Renderiza uma view para twig
     * @param string $view Arquivo da view
     * @param array $params Array com variáveis para utilizar na view
     * @param array $functions_custom Array com funções para utilizar na view
     * @return \Twig\Render
     */
    public function render($view, $params = [], $functions_custom = [])
    {
        $this->createTwig();
        $this->addFunctions($functions_custom);

        return $this->twig->render($view.'.html', $params);
    }

    protected function addFunctions($functions_custom)
    {
        // add default functions
        $functions_default = ['date','config','base_url','route_to','session'];
        foreach($functions_default as $function_default) {
            $this->twig->addFunction(new \Twig\TwigFunction($function_default, $function_default));
        }

        // add custom functions
        foreach($functions_custom as $function_custom) {
            $this->twig->addFunction(new \Twig\TwigFunction($function_custom, $function_custom));
        }
    }
}