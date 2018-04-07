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
try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	require_once dirname(__FILE__) . '/../class/marantzdenon.class.php';
	
    include_file('core', 'authentification', 'php');
	
	//ajax::init();
	
	if (init('action') == 'loadplaying') {
		$request_http = new com_http(init('url'));	
		try {
			$ret = $request_http->exec(1);
			if ($ret && strlen($ret)>0) {
				ajax::success(base64_encode ($ret));
			}
			else {
				ajax::error();
			}
		} catch (Exception $e) {
			ajax::error();
		}
	}

	if (init('action') == 'loaddisplay') {
		$jsondata = marantzdenon::getAjaxDisplayData(init('equid'));
		if ($jsondata && strlen($jsondata)>2) {
			ajax::success($jsondata);
		}
		else {
			ajax::error();
		}
	}
	
	if (init('action') == 'sendcmd') {
		$ret = marantzdenon::sendDisplayAction(init('equid'),init('cmd'));
		if ($ret) {
			ajax::success();
		}
		else {
			ajax::error();
		}
	}
	
	if (init('action') == 'getCmd') {
		$jsondata = marantzdenon::getDisplayCommandList(init('equid'));
		if ($jsondata && strlen($jsondata)>0) {
			ajax::success($jsondata);
		}
		else {
			ajax::error();
		}
	}	
	
    //throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
