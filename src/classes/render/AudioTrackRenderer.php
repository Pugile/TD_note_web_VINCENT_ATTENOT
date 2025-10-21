<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\render\Renderer;

abstract class AudioTrackRenderer implements Renderer {

	public function render(int $selector) : void {
		switch ($selector) {
			case Renderer::COMPACT : { // 1 = mode compact
				echo $this->court();
				break;
			}
			case Renderer::LONG : { // 2 = mode long
				echo $this->long();
				break;
			}
			default : { 
			}
		}
	}

	protected abstract function long() : string ;
	protected abstract function court() : string ;
}
