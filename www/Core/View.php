<?php

declare(strict_types=1);

namespace Core;

class View
{
    private $view;
    private $t;
    private $data = [];

    public function __construct($view, $t = "back")
    {
        $this->setView($view);
        $this->setTemplate($template);
    }

    public function setView(
        $view
    ) {
        $viewPath = "views/" . $view . ".view.php";
        if (file_exists($viewPath)) {
            $this->view = $viewPath;
        } else {
            die("Attention le fichier view n'existe pas " . $viewPath);
        }


    }

    public function setTemplate($template)
    {
        $templatePath = "views/templates/" . $template . ".tpl.php";
        if (file_exists($templatePath)) {
            $this->template = $templatePath;
        } else {
            die("Attention le fichier template n'existe pas " . $templatePath);
        }

    }

    public function addModal($modal, $config)
    {
        $modalPath = "views/modals/" . $modal . ".mod.php";
        if (file_exists($modalPath)) {
            include $modalPath;
        } else {
            die("Attention le fichier modal n'existe pas " . $modalPath);
        }
    }

    public function assign($key, $value)
    {
        $this->data[$key] = $value;
    }


    public function __destruct()
    {
        extract($this->data);
        include $this->template;
    }
}



