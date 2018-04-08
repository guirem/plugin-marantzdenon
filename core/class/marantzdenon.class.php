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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

include ('marantzdenon.config.php');

class marantzdenon extends eqLogic {
	/*     * *************************Attributs****************************** */


	/*     * ***********************Methode static*************************** */

	public static function cron15() {
		foreach (eqLogic::byType('marantzdenon', true) as $eqLogic) {
			$eqLogic->updateInfo();
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function preInsert() {
		$this->setCategory('multimedia', 1);
	}

	public function preUpdate() {
		if ($this->getConfiguration('ip') == '') {
			throw new Exception(__('Le champs IP ne peut etre vide', __FILE__));
		}
		if ($this->getConfiguration('favoriCount') == '') {
			throw new Exception(__('Le champs Nombre de favoris ne peut etre vide', __FILE__));
		}

		$isConnected = true;
		try {
			$infos = $this->getAmpInfo(false);
		} catch (Exception $e) { $isConnected = false; }
		if (!is_array($infos)) { $isConnected = false;	}

		if ( $this->getConfiguration('modelType') == 'auto' ) {

			if ($isConnected===true) {	// if reachable
				$model = $infos['ModelId'];
				if ( isset(MarantzDenonConfig::$INPUT_MATRIX[$model]) ) {
					$this->setConfiguration('modelType',MarantzDenonConfig::$INPUT_MATRIX[$model]);
				}
				else {
					throw new Exception(__('Modèle non reconnu ('.$model.'), veuillez choisir un modèle.', __FILE__));
				}
			}
			else {
				throw new Exception(__('Ampli non disponible. Vérifier IP ou choisir un modèle.', __FILE__));
			}
		}

		if ($isConnected===true) {
			$model = $infos['ModelId'];
			$modelInfo = (($infos['FriendlyName']=='')?'Inconnu':$infos['FriendlyName']) . ' (id=' .$model. ')';
			if ($this->getConfiguration('modelInfo') != $modelInfo) {
				$this->setConfiguration('modelInfo', $modelInfo);
			}
		}
	}

	public static function getModelDescriptions() {
		return MarantzDenonConfig::$MODEL_DESCRIPTIONS;
	}

	public function getModelDescItem($key) {
		if ( isset(MarantzDenonConfig::$MODEL_DESCRIPTIONS[$this->getConfiguration('modelType')]) )
			$mod = MarantzDenonConfig::$MODEL_DESCRIPTIONS[$this->getConfiguration('modelType')];
			if ( isset($mod[$key]) )
				return $mod[$key];
		return null;
	}

	public function postSave() {

		$cmd = $this->getCmd(null, 'ip');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('ip');
			$cmd->setIsVisible(0);
			$cmd->setName(__('IP', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'DONT');
		$cmd->save();

		$cmd = $this->getCmd(null, 'reachable');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('reachable');
			$cmd->setIsVisible(0);
			$cmd->setName(__('Accessible', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'ENERGY_STATE');
		$cmd->save();

		$cmd = $this->getCmd(null, 'power_state');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('power_state');
			$cmd->setIsVisible(0);
			$cmd->setName(__('Etat', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'ENERGY_STATE');
		$cmd->save();
		$power_state_id = $cmd->getId();

		$cmd = $this->getCmd(null, 'input');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('input');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Input', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'GENERIC');
		$cmd->save();

		$cmd = $this->getCmd(null, 'input_info');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('input_info');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Entrée', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'GENERIC');
		$cmd->save();

		$cmd = $this->getCmd(null, 'display');
		//$cmd->remove();
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('display');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Display', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'GENERIC');
		$cmd->setTemplate('dashboard','marantzdenon_display');
		$cmd->save();

		$cmd = $this->getCmd(null, 'input_netinfo');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('input_netinfo');
			$cmd->setIsVisible(0);
			$cmd->setName(__('Playing', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'GENERIC');
		$cmd->save();

		$cmd = $this->getCmd(null, 'volume');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('volume');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Volume', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('numeric');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setUnite('%');
		$cmd->setDisplay('generic_type', 'LIGHT_STATE');
		$cmd->save();
		$volume_id = $cmd->getId();

		$cmd = $this->getCmd(null, 'mute_state');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('mute_state');
			$cmd->setIsVisible(0);
			$cmd->setName(__('Mute', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'SIREN_STATE');
		$cmd->save();
		$mute_id = $cmd->getId();

		$cmd = $this->getCmd(null, 'sound_mode');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('sound_mode');
			$cmd->setIsVisible(0);
			$cmd->setName(__('Audio', __FILE__));
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'GENERIC');
		$cmd->save();

		$cmd = $this->getCmd(null, 'volume_set');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('volume_set');
			$cmd->setName(__('Volume niveau', __FILE__));
			$cmd->setIsVisible(1);
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('slider');
		$cmd->setConfiguration('minValue', MarantzDenonConfig::$MIN_VOLUME);
		if ($this->getConfiguration('volumemax')>0) {
			$cmd->setConfiguration('maxValue', $this->getConfiguration('volumemax'));
		} else {
			if ($this->getModelDescItem('MaxVolume')!==false)
				$cmd->setConfiguration('maxValue', $this->getModelDescItem('MaxVolume'));
			else
				$cmd->setConfiguration('maxValue', MarantzDenonConfig::$MAX_VOLUME);
		}
		$cmd->setValue($volume_id);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'sleep');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('sleep');
			$cmd->setName(__('Sleep', __FILE__));
			$cmd->setIsVisible(0);
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('slider');
		$cmd->setConfiguration('minValue', 0);
		$cmd->setConfiguration('maxValue', 120);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		if ($this->getConfiguration('sleepdefault')>0) {
			$cmd = $this->getCmd(null, 'sleepbtn');
			if (!is_object($cmd)) {
				$cmd = new marantzdenonCmd();
				$cmd->setLogicalId('sleepbtn');
				$cmd->setName(__('Veille', __FILE__));
				$cmd->setIsVisible(1);
				$cmd->setConfiguration('marantzdenon_cmd', true);
			}
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEqLogic_id($this->getId());
			$cmd->save();
		}
		else {
			$cmd = $this->getCmd(null, 'sleepbtn');
			if (is_object($cmd)) {
				$cmd->remove();
			}
		}

		$cmd = $this->getCmd(null, 'volume_up');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('volume_up');
			$cmd->setName(__('Volume +', __FILE__));
			$cmd->setIsVisible(1);
			$cmd->setDisplay('icon', '<i class="fa fa-volume-up"></i>');
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());

		$cmd->save();

		$cmd = $this->getCmd(null, 'volume_down');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('volume_down');
			$cmd->setName(__('Volume -', __FILE__));
			$cmd->setIsVisible(1);
			$cmd->setDisplay('icon', '<i class="fa fa-volume-down"></i>');
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'on');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('on');
			$cmd->setName(__('On', __FILE__));
			$cmd->setIsVisible(1);
			$cmd->setTemplate('dashboard', 'prise');
			$cmd->setTemplate('mobile', 'prise');
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'ENERGY_ON');
		$cmd->setValue($power_state_id);
		$cmd->save();

		$cmd = $this->getCmd(null, 'off');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('off');
			$cmd->setName(__('Off', __FILE__));
			$cmd->setIsVisible(1);
			$cmd->setTemplate('dashboard', 'prise');
			$cmd->setTemplate('mobile', 'prise');
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'ENERGY_OFF');
		$cmd->setValue($power_state_id);
		$cmd->save();

		$cmd = $this->getCmd(null, 'refresh');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('refresh');
			$cmd->setName(__('Rafraîchir', __FILE__));
			$cmd->setIsVisible(1);
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'mute_on');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('mute_on');
			$cmd->setName(__('Muet ON', __FILE__));
			$cmd->setIsVisible(1);
			$cmd->setTemplate('dashboard', 'btnCircle');
			$cmd->setTemplate('mobile', 'binaryDefault');
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'SIREN_ON');
		$cmd->setValue($mute_id);
		$cmd->save();

		$cmd = $this->getCmd(null, 'mute_off');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('mute_off');
			$cmd->setName(__('Muet OFF', __FILE__));
			$cmd->setIsVisible(1);
			$cmd->setTemplate('dashboard', 'btnCircle');
			$cmd->setTemplate('mobile', 'binaryDefault');
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'SIREN_OFF');
		$cmd->setValue($mute_id);
		$cmd->save();

		for ($favCnt = 1; $favCnt <= $this->getConfiguration('favoriCount'); $favCnt++) {
			$cmd = $this->getCmd(null, 'fav_' . $favCnt);
			if (!is_object($cmd)) {
				$cmd = new marantzdenonCmd();
				$cmd->setLogicalId('fav_' . $favCnt);
				$cmd->setName(__('Favori ' . $favCnt, __FILE__));
				$cmd->setIsVisible(1);
				$cmd->setConfiguration('marantzdenon_cmd', true);
			}
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setOrder($favCnt + 50);
			$cmd->save();
		}
		for ($favCnt = $this->getConfiguration('favoriCount')+1; $favCnt <= 9; $favCnt++) {
			$cmd = $this->getCmd(null, 'fav_' . $favCnt);
			if (is_object($cmd)) {
				$cmd->remove();
			}
		}

		$cmd = $this->getCmd(null, 'netlogo');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('netlogo');
			$cmd->setName(__('Logo', __FILE__));
			$cmd->setIsVisible(0);
			$cmd->setOrder(100);
			$cmd->setConfiguration('marantzdenon_cmd', true);
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setTemplate('dashboard','marantzdenon_playing');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		// update commands
		$nextOrder = 30;
		$model = $this->getConfiguration('modelType');
		if (isset(MarantzDenonConfig::$INPUT_MATRIX[$model])) {
			$model = MarantzDenonConfig::$INPUT_MATRIX[$model];
		}
		if (isset(MarantzDenonConfig::$INPUT_MODELS[$model])) {
			$modelInputArray = MarantzDenonConfig::$INPUT_MODELS[$model];
		}
		else {
			$modelInputArray = MarantzDenonConfig::$INPUT_MODELS['NoInput'];
		}

		// clean old si_ cmd
		foreach (MarantzDenonConfig::$INPUT_NAMES as $key => $value) {
			if ( !array_key_exists($key, $modelInputArray) ) {
				$cmd = $this->getCmd(null, 'si_' . $key);
				if (is_object($cmd)) {
					if ( $cmd->getConfiguration('marantzdenon_cmd', false) )
						$cmd->remove();
				}
			}
		}
		// create new
		foreach ($modelInputArray as $key => $value) {
			$nextOrder+=1;
			$cmd = $this->getCmd(null, 'si_'.$key);
			if (!is_object($cmd)) {
				$cmd = new marantzdenonCmd();
				$cmd->setLogicalId('si_'.$key);
				$cmd->setName($value);
				$cmd->setOrder($nextOrder);
				$cmd->setIsVisible(1);
			}
			$cmd->setConfiguration('marantzdenon_cmd', true);
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEventOnly(1);
			$cmd->setEqLogic_id($this->getId());
			$cmd->save();
		}

		$this->checkAndUpdateCmd('ip', $this->getConfiguration('ip'));
		$this->checkAndUpdateCmd('netlogo', 'http://' . $this->getConfiguration('ip') . MarantzDenonConfig::$LOGO_URL);
		$this->checkAndUpdateCmd('display', $this->getId());

		$this->updateInfo();
	}

	public function getAmpInfo($lite=true) {
		if ($lite===true) {
			$zone = 'MainZone';
			if ($this->getConfiguration('zone', 'main') != 'main') {
				$zone = 'Zone'.$this->getConfiguration('zone');
			}
			$request_http = new com_http('http://' . $this->getConfiguration('ip') . '/goform/form'.$zone.'_'.$zone.'XmlStatusLite.xml');
		} else {
			$zone = '';
			if ($this->getConfiguration('zone', 'main') != 'main') {
				$zone = '?ZoneName=ZONE' . $this->getConfiguration('zone');
			}
			$request_http = new com_http('http://' . $this->getConfiguration('ip') . '/goform/formMainZone_MainZoneXml.xml' . $zone);
		}
		try {
			$result = trim($request_http->exec(1));
		} catch (Exception $e) {
			if ($this->getConfiguration('canBeShutdown') == 1) {
				return;
			} else {
				throw new $e;
			}
		}
		$xml = simplexml_load_string($result);
		$data = json_decode(json_encode(simplexml_load_string($result)), true);
		$data['VideoSelectLists'] = array();
		if (is_array($xml->VideoSelectLists->value)) {
			foreach ($xml->VideoSelectLists->value as $VideoSelectList) {
				$data['VideoSelectLists'][(string) $VideoSelectList["index"]] = (string) $VideoSelectList;
			}
		}
		foreach ($data as $key => $value) {
			if (isset($value['value'])) {
				$data[$key] = $value['value'];
			}
		}
		return $data;
	}


	public function getAmpInfoNet() {
		$zone = '';
		if ($this->getConfiguration('zone', 'main') != 'main') {
			$zone = '?ZoneName=ZONE'.$this->getConfiguration('zone');
		}
		$request_http = new com_http('http://' . $this->getConfiguration('ip') . '/goform/formNetAudio_StatusXml.xml' . $zone);
		try {
			$result = trim($request_http->exec(1));
		} catch (Exception $e) {
			if ($this->getConfiguration('canBeShutdown') == 1) {
				return;
			} else {
				throw new $e;
			}
		}
		$data = array(
				'NETINPUT' => '',
				'NETINFO' => ''
			);

		$xml = simplexml_load_string($result);
		if ( !$xml->szLine->value[8] || $xml->szLine->value[8] == '') {
			if ($xml->szLine->value[0]) {
				$data['NETINFO'] = $xml->szLine->value[0];
			}
			if ($xml->szLine->value[1] && $xml->szLine->value[1] != '') {
				$data['NETINFO'] = trim( $xml->szLine->value[1] );
			}
		}
		if ($xml->NetFuncSelect->value) {
			$data['NETINPUT'] = trim( $xml->NetFuncSelect->value );
		}
		return $data;
	}

	public function updateInfo() {
		if ($this->getConfiguration('ip') == '') {
			return;
		}
		try {
			$infos = $this->getAmpInfo(true);
		} catch (Exception $e) {
			$this->checkAndUpdateCmd('reachable', 0);
			$this->checkAndUpdateCmd('power_state', 0);
			$this->checkAndUpdateCmd('input', 'Unavailable');
			$this->checkAndUpdateCmd('input_info', 'Unavailable');
			return;
		}
		if (!is_array($infos)) {
			$this->checkAndUpdateCmd('reachable', 0);
			$this->checkAndUpdateCmd('power_state', 0);
			$this->checkAndUpdateCmd('input', 'Unavailable');
			$this->checkAndUpdateCmd('input_info', 'Unavailable');
			return;
		}

		$this->checkAndUpdateCmd('reachable', 1);

		if ( !isset($infos['InputFuncSelect']) || !isset($infos['MasterVolume']) || !isset($infos['Mute']) || !isset($infos['Power']) ) {
			$infos2 = $this->getAmpInfo(false);
			if (is_array($infos2)) {
				$infos = array_merge($infos, $infos2);
			}
		}

		$state = 0;
		if (isset($infos['Power'])) {
			$state = ($infos['Power'] == 'ON') ? 1 : 0;
			$this->checkAndUpdateCmd('power_state', $state);
		}
		else if (isset($infos['ZonePower'])) {
			$state = ($infos['ZonePower'] == 'ON') ? 1 : 0;
			$this->checkAndUpdateCmd('power_state', $state);
		}

		if (isset($infos['InputFuncSelect'])) {
			$tmpFuncValRaw = $infos['InputFuncSelect'];
			if ( !array_key_exists($tmpFuncValRaw, MarantzDenonConfig::$INPUT_NAMES) ) {
				$tmpFuncVal = $tmpFuncValRaw;
			} else {
				$tmpFuncVal = MarantzDenonConfig::$INPUT_NAMES[ $tmpFuncValRaw ];
			}

			$tmpFuncInfoVal = $tmpFuncVal;
			$tmpNetInfo = '';
			$tmpNetVal = '';
			$isNet = false;
			if ( $tmpFuncValRaw == 'NET' ) {
				$isNet = true;
				$netdata = $this->getAmpInfoNet();
				$tmpNetVal = $netdata['NETINPUT'];
				$tmpNetInfo = ($netdata['NETINFO']=='')?'':($netdata['NETINFO']);
				$tmpFuncVal  = MarantzDenonConfig::$INPUT_NAMES[ $tmpNetVal ];
				$tmpFuncValRaw = $tmpNetVal;
				//$tmpFuncInfoVal = ($tmpFuncVal=='') ? $tmpNetVal : $tmpFuncVal . $tmpNetInfo;
				$tmpFuncInfoVal = ($tmpFuncVal=='') ? $tmpNetVal : $tmpFuncVal;
			}
			$this->checkAndUpdateCmd('input', $tmpFuncValRaw);
			$this->checkAndUpdateCmd('input_info', $tmpFuncInfoVal);
			$this->checkAndUpdateCmd('input_netinfo', $tmpNetInfo);

		}

		if (isset($infos['MasterVolume'])) {
			$vol = $infos['MasterVolume'];
			$this->checkAndUpdateCmd('volume', ( (floatval($vol)<0)? round(floatval($vol),0)+79 : round(floatval($vol),0) ));
		}

		if (isset($infos['Mute'])) {
			$this->checkAndUpdateCmd('mute_state', ($infos['Mute']=='off')?0:1 );
		}

		if (isset($infos['selectSurround'])) {
			$this->checkAndUpdateCmd('sound_mode', $infos['selectSurround']);
		}
	}

	public static function getAjaxDisplayData($id) {
		$eqLogic = eqLogic::byId($id);
		if ($eqLogic) {
			return $eqLogic->getDisplayData();
		}
		return 'Error fetching '.$id;
	}

	public static function getDisplayCommandList($id) {
		$ret = array();
		$eqLogic = eqLogic::byId($id);
		if ($eqLogic) {
			$cmds = $eqLogic->getCmd('action', null, true);
			$input = array();
			$fav = array();
			foreach ($cmds as $cmd) {
				$lid = $cmd->getLogicalId();
				if ( strpos($lid, 'si_') === 0 )
					$input[$cmd->getLogicalId()] = $cmd->getName();
				if ( strpos($lid, 'fav_') === 0 )
					$fav[$cmd->getLogicalId()] = $cmd->getName();
			}
		}
		$ret['input'] = $input;
		$ret['fav'] = $fav;
		return json_encode($ret);
	}


	public static function sendDisplayAction($id, $cmd) {
		$eqLogic = eqLogic::byId($id);
		if ($eqLogic) {
			$cmd = $eqLogic->getCmd(null, $cmd);
			if (is_object($cmd)) {
				$cmd->execute();
				return true;
			}
		}
		return false;
	}

	public function getDisplayData() {
		$data = array();
		$data['mute'] = false;
		$data['volume'] = false;
		$data['state'] = '';
		$data['surround'] = false;
		$data['input'] = '';
		$data['netinfo'] = false;
		$data['online'] = true;
		$data['playingstate'] = false;

		$online = true;
		try {
			$infos = $this->getAmpInfo(true);
		} catch (Exception $e) {
			$online = false;
		}
		if (!is_array($infos)) {
			$online = false;
		}

		if ($online===true) {
			if ( !isset($infos['InputFuncSelect']) || !isset($infos['MasterVolume']) || !isset($infos['Mute']) || !isset($infos['Power']) ) {
				$infos2 = $this->getAmpInfo(false);
				if (is_array($infos2)) {
					$infos = array_merge($infos, $infos2);
				}
			}

			$state = 0;
			if (isset($infos['Power'])) {
				$state = ($infos['Power'] == 'ON') ? 1 : 0;
			}
			else if (isset($infos['ZonePower'])) {
				$state = ($infos['ZonePower'] == 'ON') ? 1 : 0;
			}
			$data['state'] = ($state)?true:'EN VEILLE';

			if (isset($infos['InputFuncSelect'])) {
				$tmpFuncValRaw = $infos['InputFuncSelect'];
				if ( !array_key_exists($tmpFuncValRaw, MarantzDenonConfig::$INPUT_NAMES) ) {
					$tmpFuncVal = $tmpFuncValRaw;
				} else {
					$tmpFuncVal = MarantzDenonConfig::$INPUT_NAMES[ $tmpFuncValRaw ];
				}

				$tmpFuncInfoVal = $tmpFuncVal;
				$tmpNetInfo = '';
				$tmpNetVal = '';
				if ( $tmpFuncValRaw == 'NET' ) {
					$netdata = $this->getAmpInfoNet();
					$tmpNetVal = $netdata['NETINPUT'];
					$tmpNetInfo = ($netdata['NETINFO']=='')?'':($netdata['NETINFO']);
					$tmpFuncVal  = MarantzDenonConfig::$INPUT_NAMES[ $tmpNetVal ];
					$tmpFuncValRaw = $tmpNetVal;
					$tmpFuncInfoVal = ($tmpFuncVal=='') ? $tmpNetVal : $tmpFuncVal;
				}
				$data['input'] = $tmpFuncVal;
				$data['netinfo'] = $tmpNetInfo;
			}

			if (isset($infos['MasterVolume'])) {
				$data['volume'] = round(floatval($infos['MasterVolume']),0)+79;
			}

			if (isset($infos['Mute'])) {
				$data['mute'] = ($infos['Mute']=='off')?false:true;
			}

			if (isset($infos['selectSurround'])) {
				$data['surround'] = $infos['selectSurround'];
			}

			$data['playinglogo'] = 'R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
			$request_http = new com_http('http://' . $this->getConfiguration('ip') . MarantzDenonConfig::$LOGO_URL);
			try {
				$ret = $request_http->exec(1);
				if ($ret && strlen($ret)>0) {
					$data['playinglogo'] = base64_encode ($ret);
					$data['playingstate'] = true;
				}
			} catch (Exception $e) {}

		}
		else {
			$data['online'] = 'Inaccessible';
		}

		return json_encode($data);
	}


	/*     * **********************Getteur Setteur*************************** */
}

class marantzdenonCmd extends cmd {
	/*     * *************************Attributs****************************** */
	const URL_POST = '/goform/formiPhoneAppDirect.xml';
	const URL_CALLFAVORITE = '/goform/formiPhoneAppFavorite_Call.xml';
	const URL_CALLNETCOMMAND = '/goform/formiPhoneAppNetAudioCommand.xml';

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = array()) {
		$eqLogic = $this->getEqLogic();
		$IP = $eqLogic->getConfiguration('ip');
		$zone = '';
		if ($eqLogic->getConfiguration('zone', 'main') != 'main') {
			$zone = 'Z'.$eqLogic->getConfiguration('zone');
		}

		$type = $this->getType();
		$cmds = $this->getLogicalId();
		switch ($this->getSubType()) {
			case 'slider':
				$cmds = trim(str_replace('#slider#', $_options['slider'], $cmds));
				break;
			case 'select':
				$cmds = trim(str_replace('#listValue#', $_options['select'], $cmds));
				break;
			case 'message':
				$cmds = trim(str_replace('#message#', $_options['message'], $cmds));
				$cmds = trim(str_replace('#title#', $_options['title'], $cmds));
				break;
		}

		$cmds = explode(',', $cmds);

		$index=0;
		$delay=0;
		foreach ($cmds as $cmd) {
			$cmd = trim($cmd);
			$index++;

			if ($type == 'action') {
				if ($cmd == 'on') {
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'PWON':$zone.'ON'));
					$ret = $this->http_exec_wrapper($request_http, 4);
					if ($ret && $eqLogic->getConfiguration('volumedefault')>0) {
						sleep(8);
						$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'MV':$zone) .str_pad( $eqLogic->getConfiguration('volumedefault'), 2, "0", STR_PAD_LEFT ));
						$this->http_exec_wrapper($request_http, 2);
					}
					$delay=2;
				} else if ($cmd == 'off') {
					if ($eqLogic->getConfiguration('volumedefault')>0) {
						$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'MV':$zone) .str_pad( $eqLogic->getConfiguration('volumedefault'), 2, "0", STR_PAD_LEFT ));
						$this->http_exec_wrapper($request_http, 2);
						sleep(1);
					}
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'PWSTANDBY':$zone.'OFF'));
					$ret = $this->http_exec_wrapper($request_http, 4);
					if ($ret) $delay=5;
				} else if ($cmd == 'volume_set') {
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'MV':$zone) .str_pad( min($_options['slider'],$eqLogic->getConfiguration('volumemax')), 2, "0", STR_PAD_LEFT ));
					$request_http->exec();
				} else if ( strpos($cmd, 'volume_set_') === 0) {
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'MV':$zone) .str_pad( min(substr($cmd, -1),$eqLogic->getConfiguration('volumemax')), 2, "0", STR_PAD_LEFT ));
					$request_http->exec();
				} else if ($cmd == 'volume_up') {
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'MV':$zone).'UP');
					for ($i = 0; $i <= $eqLogic->getConfiguration('volumestep',1); $i++) {
						$request_http->exec();
						usleep(250000);
					}
				} else if ($cmd == 'volume_down') {
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'MV':$zone).'DOWN');
					for ($i = 0; $i <= $eqLogic->getConfiguration('volumestep',1); $i++) {
						$request_http->exec();
						usleep(250000);
					}
				} else if ($cmd == 'mute_on') {
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.$zone.'MUON');
					$request_http->exec();
				} else if ($cmd == 'mute_off') {
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.$zone.'MUOFF');
					$request_http->exec();
				} else if ( strpos($cmd, 'fav_') === 0) {	// is a fav call
					$request_http = new com_http('http://' . $IP . self::URL_CALLFAVORITE . '?0' . substr($cmd, -1) );
					$ret = $this->http_exec_wrapper($request_http, 2);
					if ($ret===false) {	// alternative way of calling favorites (eg: for AVR)
						$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.(($zone=='')?'ZM':$zone).'FAVORITE'.substr($cmd, -1));
						$request_http->exec();
					}
				} else if ($cmd == 'sleep') {
					$sleepval = str_pad($_options['slider'], 3, "0", STR_PAD_LEFT );
					if ($sleepval=='000') {
						$sleepval='OFF';
					}
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.$zone.'SLP'.$sleepval);
					$request_http->exec();
				} else if ($cmd == 'sleepbtn') {
					$sleepval = str_pad( $eqLogic->getConfiguration('sleepdefault') , 3, "0", STR_PAD_LEFT );
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'.$zone.'SLP'.$sleepval);
					$request_http->exec();
				} else if ( strpos($cmd, 'si_') === 0) {	// is a input change call
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?'. (($zone=='')?'SI':$zone) . strtoupper(str_replace('si_','',$cmd)) );
					$request_http->exec();
					$delay=2;
				} else if ( is_numeric($cmd) ) {
					sleep($cmd);
				} else if ( strpos($cmd, 'cur_') === 0) {
					$cur = str_replace('cur_', '', $cmd);
					$request_http = new com_http('http://' . $IP . self::URL_CALLNETCOMMAND . '?Cur' . ucfirst(strtolower($cur)) );
					$request_http->exec();
				} else if ($cmd == 'refresh' || $cmd == 'reachable') {
					// do nothing
				} else {	// other commands
					$request_http = new com_http('http://' . $IP . self::URL_POST . '?' . urlencode(strtoupper($cmd)) );
					$request_http->exec();
				}
				if ( $index==count($cmds) ) {	// update on last cmd
					sleep(1+$delay);
					$eqLogic->updateInfo();
				}
			}
			else {		// if 'info'
				$eqLogic->updateInfo();
			}
		}
	}

	function http_exec_wrapper($request_http, $timeout=2) {
		try {
			$request_http->exec($timeout);
			return true;
		} catch (Exception $e) {
			if ($this->getConfiguration('canBeShutdown') == 1) {
				return false;
			} else {
				throw new $e;
			}
		}
	}
}
