<?php

declare(strict_types=1);


namespace Controller;

//use Core\Routing;
use Model\User;
use Core\View;
use Core\Validator;

class UserController
{

    public function defaultAction()
    {
        echo "users default";
    }

    public function addAction()
    {
        $user = new User();
        $form = $user->getRegisterForm(); // mettre getRegisterForm dans une classe a part


        $view = new View("addUser", "front");
        $view->assign("form", $form);
    }

    public function saveAction()
    {





        $user = new User();
        $form = $user->getRegisterForm();
        $method = strtoupper($form["config"]["method"]);
        $data = $GLOBALS["_" . $method];


        if ($_SERVER['REQUEST_METHOD'] == $method && !empty($data)) {

            $validator = new Validator($form, $data);
            $form["errors"] = $validator->errors;

            if (empty($errors)) {
                $user->setFirstname($data["firstname"]);
                $user->setLastname($data["lastname"]);
                $user->setEmail($data["email"]);
                $user->setPwd($data["pwd"]);
                $user->save();
            }


        }

        $view = new View("addUser", "front");
        $view->assign("form", $form);
    }


    public function loginAction()
    {
        $user = new Users();
        $form = $user->getLoginForm();


        $method = strtoupper($form["config"]["method"]);
        $data = $GLOBALS["_" . $method];
        if ($_SERVER['REQUEST_METHOD'] == $method && !empty($data)) {

            $validator = new Validator($form, $data);
            $form["errors"] = $validator->errors;

            if (empty($errors)) {
                $token = md5(substr(uniqid() . time(), 4, 10) . "mxu(4il");
                // TODO: connexion
            }

        }

        $view = new View("loginUser", "front");
        $view->assign("form", $form);
    }


    public function forgetPasswordAction()
    {
        $view = new View("forgetPasswordUser", "front");
    }
}