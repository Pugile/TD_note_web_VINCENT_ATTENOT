<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\lists\Album;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

try {
	$piste1 = new AlbumTrack('titre1a', 't-rex-roar.mp3', 'album1', 33);
	$piste1->setArtiste('artiste1');
	$piste1->setDuree(120);

	$piste2 = new PodcastTrack('titre2p', 't-rex-roar.mp3', 'auteur2', '01/01/2001');
	$piste2->setDuree(180);

	// Album
	$album = new Album('Mon Album', [$piste1], 'artiste1', '2020-01-01');
	$albumRenderer = new AudioListRenderer($album);
// 	echo '<h2>Affichage Album (AudioListRenderer)</h2>';
	$albumRenderer->render(1);

	// Playlist
	$playlist = new Playlist('Ma Playlist');
	$playlist->ajouterPiste($piste1);
	$playlist->ajouterPiste($piste2);
	$playlistRenderer = new AudioListRenderer($playlist);
// 	echo '<h2>Affichage Playlist (AudioListRenderer)</h2>';
	$playlistRenderer->render(1);



} catch (InvalidPropertyNameException $e) {
// 	echo 'Erreur propriété : ' . $e->getMessage() . "<br>";
// 	echo nl2br($e->getTraceAsString());
} catch (InvalidPropertyValueException $e) {
// 	echo 'Erreur valeur : ' . $e->getMessage() . "<br>";
// 	echo nl2br($e->getTraceAsString());
} catch (Exception $e) {
// 	echo 'Autre erreur : ' . $e->getMessage() . "<br>";
// 	echo nl2br($e->getTraceAsString());
}




