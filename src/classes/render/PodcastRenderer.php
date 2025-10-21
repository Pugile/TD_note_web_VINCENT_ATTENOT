<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\PodcastTrack;

class PodcastRenderer extends AudioTrackRenderer {
	private PodcastTrack $piste;
    
	public function __construct(PodcastTrack $piste) {
		$this->piste = $piste;
	}

	protected function long() : string {
		$p = $this->piste;
		return "<div>{$p->titre} - by {$p->auteur} <audio controls src='{$p->chemin}'></audio></div>\n";
	}
	protected function court() : string {
		$p = $this->piste;
		return "<div>{$p->titre} - by {$p->auteur} </div>\n";
	}
}
