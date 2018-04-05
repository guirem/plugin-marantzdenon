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

class MarantzDenonConfig {
	/*     * *************************Attributs****************************** */

	public static $MAX_VOLUME = 98;//19;
	public static $MIN_VOLUME = 0;//-79;
	
	public static $LOGO_EMPTY = '<img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="0" height="0" alt="">';
	
	// All possible names of input
	public static $INPUT_NAMES = array(
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

	// match betwenn device model id and predefined models (used for auto mode)
	public static $INPUT_MATRIX = array(	
			'0'  => 'NoInput', 
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

	// predefined models
	public static $INPUT_MODELS = array(
			'NoInput' => array(),
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
}
