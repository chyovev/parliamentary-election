<?php
class ExtendedSmarty extends Smarty {

    ///////////////////////////////////////////////////////////////////////////
    public function renderFullPage(string $contentFile, array $viewVars = []): string {
        $this->assign($viewVars);

        $layout  = $this->fetch('layout/header.tpl');
        $layout .= $this->fetch($contentFile);
        $layout .= $this->fetch('layout/footer.tpl');

        return $layout;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function displayFullPage(string $contentFile, array $viewVars = []): void {
        $page = $this->renderFullPage($contentFile, $viewVars);
        print($page);
        die;
    }

}
