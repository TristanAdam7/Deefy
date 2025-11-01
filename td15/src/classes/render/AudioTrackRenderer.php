<?php
declare(strict_types = 1);

namespace iutnc\deefy\render;

abstract class AudioTrackRenderer implements Renderer {

    public function render(int $selector): string {
        if ($selector === self::COMPACT) {
            return $this->renderCompact();
        } elseif ($selector === self::LONG) {
            return $this->renderLong();
        } else {
            return "<p>Mode d'affichage inconnu</p>";
        }
    }

    abstract protected function renderCompact(): string;
    abstract protected function renderLong(): string;
}