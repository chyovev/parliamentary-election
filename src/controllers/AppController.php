<?php

abstract class AppController {

    private $smarty;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(ExtendedSmarty $smarty) {
        $this->smarty = $smarty;
    }

    ///////////////////////////////////////////////////////////////////////////
    // render full page as string and show it on screen
    protected function displayFullPage(string $template, array $viewVars = []): void {
        $this->smarty->displayFullPage($template, $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    // render full page as string
    protected function renderFullPage(string $template, array $viewVars = []): string {
        return $this->smarty->renderFullPage($template, $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function renderTemplate(string $template, array $viewVars = []): string {
        return $this->smarty->render($template, $viewVars);
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function renderJSONContent($array): void {
        if (!is_array($array)) {
            $array = [$array];
        }

        header('Content-Type: application/json');
        print(json_encode($array));
        exit;
    }

}