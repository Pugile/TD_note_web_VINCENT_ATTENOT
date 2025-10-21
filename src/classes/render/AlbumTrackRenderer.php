<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\AlbumTrack;

class AlbumTrackRenderer extends AudioTrackRenderer {
	private AlbumTrack $piste;
    
	public function __construct(AlbumTrack $piste) {
		$this->piste = $piste;
	}

	protected function long() : string {
		$p = $this->piste;
		return "<div>{$p->titre} - by {$p->artiste} (from {$p->album}) <audio controls src='{$p->chemin}'></audio></div>\n";
	}
	protected function court() : string {
		$p = $this->piste;
		return "<div>{$p->titre} - by {$p->artiste} (from {$p->album}) </div>\n";
	}
}
