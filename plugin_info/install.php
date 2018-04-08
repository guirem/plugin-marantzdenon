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
	linkTemplate('cmd.info.string.marantzdenon_display.html');
	linkTemplate('cmd.info.string.marantzdenon_playing.html');
	// Todo, update external commands using this template

	foreach (marantzdenon::byType('marantzdenon') as $marantzdenon) {
		try {
			$marantzdenon->save();
		} catch (Exception $e) {
		}
	}

	message::add('marantzdenon', "Marantz Denon updated !", null, null);
}

function marantzdenon_install() {
	linkTemplate('cmd.info.string.marantzdenon_display.html');
	linkTemplate('cmd.info.string.marantzdenon_playing.html');
}

function linkTemplate($templateFilename) {
	log::add('marantzdenon','info',"Copie du template " . $templateFilename);
	$pathSrc = dirname(__FILE__) . '/../core/template/dashboard/'.$templateFilename;
	$pathDest = dirname(__FILE__) . '/../../../core/template/dashboard/'.$templateFilename;

	if (!file_exists($pathDest)) {
		shell_exec('ln -s '.$pathSrc. ' '. $pathDest);
	}
	//shell_exec('cp -f '.$pathSrc. ' '. $pathDest);
	//shell_exec('chmod +x '.$pathDest);
}

function marantzdenon_remove() {
	unlinkTemplate('cmd.info.string.marantzdenon_display.html');
	unlinkTemplate('cmd.info.string.marantzdenon_playing.html');
}

function unlinkTemplate($templateFilename) {
	log::add('marantzdenon','info',"Suppression du template " . $templateFilename);
	$path = dirname(__FILE__) . '/../../../core/template/dashboard/'.$templateFilename;
	$allowWritePath = config::byKey('allowWriteDir', 'widget');

	if (file_exists($path)) {
		unlink($path);
	}
}

?>
