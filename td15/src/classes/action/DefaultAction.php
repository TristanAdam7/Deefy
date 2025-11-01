<?php

namespace iutnc\deefy\action;
class DefaultAction extends Action{

    public function executeGet(): string {
        return "<h2>Bienvenue sur DeefyApp</h2>";
    }

    public function executePost(): string {
        return $this->executeGet();
    }
}