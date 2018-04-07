<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function marantzdenon_update() {
	copyTemplate('cmd.info.string.marantzdenon_display.html');
	copyTemplate('cmd.info.string.marantzdenon_playing.html');
	// Todo, update external commands using this template
	
	foreach (marantzdenon::byType('marantzdenon') as $marantzdenon) {
		try {
			$marantzdenon->save();
		} catch (Exception $e) {
		}
	}
}

function marantzdenon_install() {
	copyTemplate('cmd.info.string.marantzdenon_display.html');
	copyTemplate('cmd.info.string.marantzdenon_playing.html');
}

function copyTemplate($templateFilename) {
	message::add('marantzdenon', "Copie du template " . $templateFilename, null, null);
	$pathSrc = realpath(dirname(__FILE__) . '/../core/template/dasboard/'.$templateFilename);
	$pathDest = realpath(dirname(__FILE__) . '/../../../core/template/dasboard/'.$templateFilename);
	if (!rcopy($pathSrc, $pathDest, true, array(), true)) {
		//throw new Exception(__('Impossible de copier ', __FILE__) . $templateFilename);
		message::add('marantzdenon', "Imossible de copier le template " . $templateFilename, null, null);
		return;
	}
}

function marantzdenon_remove() {
	removeTemplate('cmd.info.string.marantzdenon_display.html');
	removeTemplate('cmd.info.string.marantzdenon_playing.html');
}

function removeTemplate($templateFilename) {
	message::add('marantzdenon', "Suppression du template " . $templateFilename, null, null);
	$path = realpath(dirname(__FILE__) . '/../../../core/template/dasboard/'.$templateFilename);
	$allowWritePath = config::byKey('allowWriteDir', 'widget');
	if (!hadFileRight($allowWritePath, $path)) {
		//throw new Exception(__('Vous n\'etes pas autoriser à écrire : ', __FILE__) . $path);
		return;
	}
	if (file_exists($path)) {
		unlink($path);
	}
}

?>
