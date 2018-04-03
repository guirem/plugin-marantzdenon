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

class marantzdenon extends eqLogic {
	/*     * *************************Attributs****************************** */

	const MAX_VOLUME = 98;//19;
	const MIN_VOLUME = 0;//-79;
	
	//const URL_GET = '/goform/formMainZone_MainZoneXml.xml';
	//const URL_GET2 = '/goform/formMainZone_MainZoneXmlStatusLite.xml';
	//const URL_GETNETPLAYING = '/goform/formNetAudio_StatusXml.xml';
	
	const INPUT_TYPE = array(
				'NET' => 'Network',
				'USB/IPOD' => 'iPod/USB',
				'USB' => 'USB',
				'REARUSB' => 'USB Arrière',
				'SERVER' => 'Media Server',
				'DIGITALIN1' => 'Optique',
				'DIGITALIN2' => 'Optique 2',
				'ANALOGIN' => 'Analogique',
				'BLUETOOTH' => 'Bluetooth',
				'BT' => 'Bluetooth',
				'IRADIO' => 'Internet Radio',
				'SAT/CBL' => 'CBL/SAT',
				'DVD' => 'DVD/Blu-ray',
				'BD' => 'Blu-ray',
				'GAME' => 'Game',
				'AUX1' => 'Aux 1',
				'AUX2' => 'Aux 2',
				'MPLAY' => 'Media Player',
				'SPOTIFY' => 'Spotify',
				'FLICKR' => 'FlickR',
				'FAVORITES' => 'Favoris',
				'TV' => 'TV Audio',
				'TUNER' => 'Tuner',
				'NETHOME' => 'Online Music',
				'BT' => 'Bluetooth',
				'IRP' => 'Internet Radio',
				'CD' => 'CD',
				'PHONO' => 'Phono',
			);

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
	}

	public function postSave() {
		
		$cmd = $this->getCmd(null, 'reachable');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('reachable');
			$cmd->setIsVisible(0);
			$cmd->setName(__('Accessible', __FILE__));
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
			$cmd->setIsVisible(0);
			$cmd->setName(__('Input', __FILE__));
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
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'GENERIC');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'input_netinfo');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('input_netinfo');
			$cmd->setIsVisible(0);
			$cmd->setName(__('Playing', __FILE__));
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
			$cmd->setIsVisible(0);
			$cmd->setName(__('Volume', __FILE__));
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
		}
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'SIREN_STATE');
		$cmd->save();
		$mute_id = $cmd->getId();
		
		/*
		$cmd = $this->getCmd(null, 'sound_mode');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('sound_mode');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Audio', __FILE__));
		}
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('generic_type', 'GENERIC');
		$cmd->save();
		*/

		$cmd = $this->getCmd(null, 'volume_set');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('volume_set');
			$cmd->setName(__('Volume niveau', __FILE__));
			$cmd->setIsVisible(1);
		}
		$cmd->setType('action');
		$cmd->setSubType('slider');
		$cmd->setConfiguration('minValue', self::MIN_VOLUME);
		if ($this->getConfiguration('volumemax')>=0) {
			$cmd->setConfiguration('maxValue', $this->getConfiguration('volumemax'));
		} else {
			$cmd->setConfiguration('maxValue', self::MAX_VOLUME);
		}
		$cmd->setValue($volume_id);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'sleep');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('sleep');
			$cmd->setName(__('Sleep', __FILE__));
			$cmd->setIsVisible(1);
		}
		$cmd->setType('action');
		$cmd->setSubType('slider');
		$cmd->setConfiguration('minValue', 0);
		$cmd->setConfiguration('maxValue', 300);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'volume_up');
		if (!is_object($cmd)) {
			$cmd = new marantzdenonCmd();
			$cmd->setLogicalId('volume_up');
			$cmd->setName(__('Volume +', __FILE__));
			$cmd->setIsVisible(1);
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
			}
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setOrder($favCnt + 40);
			$cmd->save();
		}
		for ($favCnt = $this->getConfiguration('favoriCount')+1; $favCnt <= 9; $favCnt++) {
			$cmd = $this->getCmd(null, 'fav_' . $favCnt);
			if (is_object($cmd)) {
				$cmd->remove();
			}
			
		}
		
		$convert = array(	// predefined models
			'0'  => '', 
			'30' => 'Marantz_M-CR511',
			'3X' => 'Marantz_M-CR611',
			'3'  => 'Denon_Tuner', 
			'8' => 'Denon_Tuner', 
			'9' => 'Denon_Tuner', 
			'6' => 'Denon_Phono', 
			'11' => 'Denon_Phono', 
			'12' => 'Denon_Phono', 
			'13' => 'Denon_Phono'
		);

		$inputModel = array(
			'BasicHomeCinema' => array(
				'SAT/CBL' => 'CBL/SAT',
				'DVD' => 'DVD/Blu-ray',
				'BD' => 'Blu-ray',
				'GAME' => 'Game',
				'AUX1' => 'AUX',
				'AUX2' => 'AUX2',
				'MPLAY' => 'Media Player',
				'SPOTIFY' => 'Spotify',
				'USB/IPOD' => 'iPod/USB',
				'TV' => 'TV Audio',
				'TUNER' => 'Tuner',
				'NETHOME' => 'Online Music',
				'BT' => 'Bluetooth',
				'IRP' => 'Internet Radio',
				'CD' => 'CD',
			),
			'BasicNotHomeCinema' => array(
				'AUX1' => 'AUX',
				'AUX2' => 'AUX2',
				'MPLAY' => 'Media Player',
				'SPOTIFY' => 'Spotify',
				'USB/IPOD' => 'iPod/USB',
				'TUNER' => 'Tuner',
				'NETHOME' => 'Online Music',
				'BT' => 'Bluetooth',
				'IRP' => 'Internet Radio',
				'CD' => 'CD',
			),
			'NoInput' => array(),
			'Marantz_M-CR511' => array(	// Marantz  M-CR611
				'USB/IPOD' => 'iPod/USB',
				'REARUSB' => 'USB Arrière',
				'DIGITALIN1' => 'Optique',
				'SERVER' => 'Media Server',
				'ANALOGIN' => 'Analogique',
				'BLUETOOTH' => 'Bluetooth',
				'IRADIO' => 'iRadio',
			),
			'Marantz_M-CR611' => array(	// Marantz  M-CR611
				'USB/IPOD' => 'iPod/USB',
				'DIGITALIN1' => 'Optique',
				'ANALOGIN' => 'Analogique',
				'BLUETOOTH' => 'Bluetooth',
				'IRADIO' => 'iRadio',
				'TUNER' => 'Tuner',
				'CD' => 'CD',
				'SERVER' => 'Media Server',
				'REARUSB' => 'USB Arrière',
			),
			'Denon_Tuner' => array(
				'SAT/CBL' => 'CBL/SAT',
				'DVD' => 'DVD/Blu-ray',
				'BD' => 'Blu-ray',
				'GAME' => 'Game',
				'AUX1' => 'AUX1',
				'AUX2' => 'AUX2',
				'MPLAY' => 'Media Player',
				'USB/IPOD' => 'iPod/USB',
				'TV' => 'TV Audio',
				'TUNER' => 'Tuner',
				'NETHOME' => 'Online Music',
				'BT' => 'Bluetooth',
				'IRP' => 'Internet Radio',
				'CD' => 'CD',
				'SERVER' => 'Media Server',
			),
			'Denon_Phono' => array(
				'SAT/CBL' => 'CBL/SAT',
				'DVD' => 'DVD/Blu-ray',
				'BD' => 'Blu-ray',
				'GAME' => 'Game',
				'AUX1' => 'AUX1',
				'AUX2' => 'AUX2',
				'MPLAY' => 'Media Player',
				'USB/IPOD' => 'iPod/USB',
				'TV' => 'TV Audio',
				'NETHOME' => 'Online Music',
				'BT' => 'Bluetooth',
				'IRP' => 'Internet Radio',
				'CD' => 'CD',
				'PHONO' => 'Phono',
			),
		);

		if ($this->getConfiguration('ip') != '') {
			$nextOrder = 20;
			$infos = $this->getAmpInfoAll();
			
			if ($infos!=false) {	// if reachable
				$model = $infos['ModelId'];
			
				$modelInfo = (($infos['FriendlyName']=='')?'Inconnu':$infos['FriendlyName']) . ' (id=' .$model. ')';
				
				if (isset($convert[$model])) {
					$model = $convert[$model];
				}
				if (isset($inputModel[$model])) {
					$modelInputArray = $inputModel[$model];
				}
				if ($this->getConfiguration('modelType') != 'auto') {
					$modelInputArray = $inputModel[$this->getConfiguration('modelType')];
				}
				if ( $this->getConfiguration('modelType') == 'auto' && !isset($inputModel[$model]) ) {
					$modelInputArray = $inputModel['BasicHomeCinema'];
				}
				// clean old si_ cmd
				foreach (self::INPUT_TYPE as $key => $value) {
					if ( !array_key_exists($key, $modelInputArray) ) {
						$cmd = $this->getCmd(null, 'si_' . $key);
						if (is_object($cmd)) {
							$cmd->remove();
						}
					}
				}
			
				// create new
				foreach ($modelInputArray as $key => $value) {
					$cmd = $this->getCmd(null, 'si_'.$key);
					if (!is_object($cmd)) {
						$cmd = new marantzdenonCmd();
						$cmd->setLogicalId('si_'.$key);
						$cmd->setName($value);
						$cmd->setIsVisible(1);
					}
					$cmd->setType('action');
					$cmd->setSubType('other');
					$cmd->setEventOnly(1);
					$cmd->setEqLogic_id($this->getId());
					$cmd->setOrder($nextOrder++);
					$cmd->save();
				}
				
				if ($this->getConfiguration('modelInfo') != $modelInfo) {
					$this->setConfiguration('modelInfo', $modelInfo);
					$this->save();
				}
			}
			$this->updateInfo();
		}
	}
	/*
	public function preSave() {
		$infos = $this->getAmpInfoAll();
			
		$modelInfo = (($infos['FriendlyName']=='')?'Inconnu':$infos['FriendlyName']) . ' (id=' .$infos['ModelId']. ')';
		if ($this->getConfiguration('modelInfo') != $modelInfo) {
			$this->setConfiguration('modelInfo', $modelInfo);
			//$this->save();
		}
	}
	*/
	public function getAmpInfoAll() {
		$request_http = new com_http('http://' . $this->getConfiguration('ip') . '/goform/formMainZone_MainZoneXml.xml');
		try {
			$result = trim($request_http->exec());
		} catch (Exception $e) {
			if ($this->getConfiguration('canBeShutdown') == 1) {
				return false;
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
	
	public function getAmpInfo() {
		$zone = 'MainZone';
		if ($this->getConfiguration('zone', 'main') != 'main') {
			$zone = 'Zone'.$this->getConfiguration('zone');
		}
		$request_http = new com_http('http://' . $this->getConfiguration('ip') . '/goform/form'.$zone.'_'.$zone.'XmlStatusLite.xml');
		try {
			$result = trim($request_http->exec(5));
		} catch (Exception $e) {
			if ($this->getConfiguration('canBeShutdown') == 1) {
				return;
			} else {
				throw new $e;
			}
		}
		$xml = simplexml_load_string($result);
		$data = json_decode(json_encode(simplexml_load_string($result)), true);
		/*
		$data['VideoSelectLists'] = array();
		if (is_array($xml->VideoSelectLists->value)) {
			foreach ($xml->VideoSelectLists->value as $VideoSelectList) {
				$data['VideoSelectLists'][(string) $VideoSelectList["index"]] = (string) $VideoSelectList;
			}
		}
		*/
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
			$result = trim($request_http->exec(5));
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
			$infos = $this->getAmpInfo();
		} catch (Exception $e) {
			$this->checkAndUpdateCmd('reachable', 0);
			$this->checkAndUpdateCmd('power_state', 0);
			$this->checkAndUpdateCmd('input_info', 'Non accessible');
			return;
		}
		if (!is_array($infos)) {
			$this->checkAndUpdateCmd('reachable', 0);
			$this->checkAndUpdateCmd('power_state', 0);
			$this->checkAndUpdateCmd('input_info', 'Non accessible');
			return;
		}
		$this->checkAndUpdateCmd('reachable', 1);
		if (isset($infos['Power'])) {
			$this->checkAndUpdateCmd('power_state', ($infos['Power'] == 'ON') ? 1 : 0);
			
		}
		if (isset($infos['InputFuncSelect'])) {
			//$tmpFuncVal = $infos['InputFuncSelect'];
			$tmpFuncVal = self::INPUT_TYPE[ $infos['InputFuncSelect'] ];
			$tmpFuncValRaw = $infos['InputFuncSelect'];
			$tmpFuncInfoVal = $tmpFuncVal;
			$tmpNetInfo = '';
			$tmpNetVal = '';
			if ( $infos['InputFuncSelect'] == 'NET' ) {
				$netdata = $this->getAmpInfoNet();
				$tmpNetVal = $netdata['NETINPUT'];
				$tmpNetInfo = ($netdata['NETINFO']=='')?'':(' (' .$netdata['NETINFO']. ')');
				$tmpFuncVal  = self::INPUT_TYPE[ $tmpNetVal ];
				$tmpFuncValRaw = $tmpNetVal;
				$tmpFuncInfoVal = ($tmpFuncVal=='') ? $tmpNetVal : $tmpFuncVal . $tmpNetInfo;
			}
			//$this->checkAndUpdateCmd('input', ($tmpFuncVal=='') ? $infos['InputFuncSelect'] : $tmpFuncVal );
			$this->checkAndUpdateCmd('input', $tmpFuncValRaw);
			$this->checkAndUpdateCmd('input_info', $tmpFuncInfoVal);
			$this->checkAndUpdateCmd('input_netinfo', $tmpNetInfo);

		}
		if (isset($infos['MasterVolume'])) {
			$vol = $infos['MasterVolume'];
			$this->checkAndUpdateCmd('volume', ( ($vol<0)? $vol+79 : $vol ));
		}
		if (isset($infos['Mute'])) {
			$this->checkAndUpdateCmd('mute_state', ($infos['Mute']=='off')?0:1 );
		}
		/*
		if (isset($infos['selectSurround'])) {
			$this->checkAndUpdateCmd('sound_mode', $infos['selectSurround']);
		}
		*/
	}
	
	public function updateCustomInfo($cmdId, $name) {
		if ($this->getConfiguration('ip') == '') {
			return;
		}
		try {
			$infos = $this->getAmpInfoAll();
		} catch (Exception $e) {
			return;
		}
		if (!is_array($infos)) {
			return;
		}
		if (isset($infos[$name])) {
			$this->checkAndUpdateCmd($cmdId, $infos[$name]);
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}

class marantzdenonCmd extends cmd {
	/*     * *************************Attributs****************************** */
	const URL_POST = '/goform/formiPhoneAppDirect.xml';
	const URL_CALLFAVORITE = '/goform/formiPhoneAppFavorite_Call.xml';
	
	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = array()) {
		$eqLogic = $this->getEqLogic();
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
		foreach ($cmds as $cmd) {
			$cmd = trim($cmd);
			$index++;
				
			if ($type == 'action') {
				if ($cmd == 'on') {
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.(($zone=='')?'PWON':$zone.'ON'));
					$ret = $this->http_exec_wrapper($request_http, 10);
					if ($ret && $eqLogic->getConfiguration('volumedefault')>0) {
						sleep(8);
						$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.(($zone=='')?'MV':$zone) .str_pad( $eqLogic->getConfiguration('volumedefault'), 2, "0", STR_PAD_LEFT ));
						$this->http_exec_wrapper($request_http, 2);
					}
				} else if ($cmd == 'off') {
					if ($eqLogic->getConfiguration('volumedefault')>0) {
						$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.(($zone=='')?'MV':$zone) .str_pad( $eqLogic->getConfiguration('volumedefault'), 2, "0", STR_PAD_LEFT ));
						$this->http_exec_wrapper($request_http, 2);
						sleep(1);
					}
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.(($zone=='')?'PWSTANDBY':$zone.'OFF'));
					$ret = $this->http_exec_wrapper($request_http, 10);
					if ($ret) sleep(5);
				} else if ($cmd == 'volume_set') {
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.(($zone=='')?'MV':$zone) .str_pad( min($_options['slider'],$eqLogic->getConfiguration('volumemax')), 2, "0", STR_PAD_LEFT ));
					$request_http->exec();
				} else if ($cmd == 'volume_up') {
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.(($zone=='')?'MV':$zone).'UP');
					$request_http->exec();
				} else if ($cmd == 'volume_down') {
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.(($zone=='')?'MV':$zone).'DOWN');
					$request_http->exec();
				} else if ($cmd == 'mute_on') {
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.$zone.'MUON');
					$request_http->exec();
				} else if ($cmd == 'mute_off') {
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.$zone.'MUOFF');
					$request_http->exec();
				} else if ( strpos($cmd, 'fav_') === 0) {	// is a fav call
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_CALLFAVORITE . '?0' . substr($cmd, -1) );
					$ret = $this->http_exec_wrapper($request_http, 2);
					if ($ret==false) {	// alternative way of calling favorites (eg: for AVR)
						$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.(($zone=='')?'ZM':$zone).'FAVORITE'.substr($cmd, -1));
						$request_http->exec();
					}
				} else if ($cmd == 'sleep') {
					$sleepval = str_pad($_options['slider'], 3, "0", STR_PAD_LEFT );
					if ($sleepval=='000') {
						$sleepval='OFF';
					}
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'.$zone.'SLP'.$sleepval);
					$request_http->exec();
				} else if ( strpos($cmd, 'si_') === 0) {	// is a input change call
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?'. (($zone=='')?'SI':$zone) . str_replace('si_','',$cmd) );
					$request_http->exec();
				} else if ( is_numeric($cmd) ) {
					sleep($cmd);
				} else if ($cmd == 'refresh' || $cmd == 'reachable') {
					// do nothing
				} else {	// other commands
					$request_http = new com_http('http://' . $eqLogic->getConfiguration('ip') . self::URL_POST . '?' . urlencode(strtoupper($cmd)) );
					$request_http->exec();
				}
				if ( $index==count($cmds) ) {	// update on last cmd
					sleep(1);
					$eqLogic->updateInfo();
				}
			}
			else {		// if 'info'
				$eqLogic->updateInfo();
				/*
				switch ($cmd) {
					case "power_state":
					case "input":
					case "input_info":
					case "input_netinfo":
					case "volume":
					case "mute_state":
						$eqLogic->updateInfo();
						break;
					default:
						$eqLogic->updateCustomInfo($this->getLogicalId(), $value);
						break;
				}
				*/
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

?>
