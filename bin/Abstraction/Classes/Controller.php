<?php

namespace bin\Abstraction\Classes;


use Smarty;

abstract class Controller
{
    public function View(string $template, $model) : string {
        $smarty = new Smarty();
        $smarty->caching = 0;
        $smarty->setTemplateDir( ROOT."/View");
        $smarty->assign('model', $model);
        return $smarty->fetch($template.'.tpl');
    }

    public function Json($entity) : string {
        return json_encode( $entity );
    }
}