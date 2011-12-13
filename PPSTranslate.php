<?php

/**
 * Simple Machines Forum (SMF)
 *
 * @package pps_smf_translate
 * @author Lukas Zurschmiede <lukas.zurschmiede@piratenpartei.ch>
 * @copyright 2011 Piratenpartei Schweiz
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 0.1
 */

/*	This file has all the needed functions to use SMF with multiple languages

	void pps_smf_translate(string &string)
		- Check the string for a valid Format and return the current users value
		- The string may be a normal string which is returned "as is"
		- If the string is in format "{$EN:English string;$DE:Deutscher Text;$XY:...}"
		  the translated string is returned
		- The Language should be in ISO-639-1 (two-letter code)
*/

function pps_smf_translate(&$string) {
	global $user_info, $firephp;
	if (!array_key_exists('iso639-1', $user_info)) {
		$user = array('language' => $user_info['language']);
		pps_smf_parselanguage($user);
	}

	$match = array();
	if (preg_match('/\{.*?(\$'.strtoupper($user_info['iso639-1']).'\:)(.*?)(;\$|;?\})/smi', $string, $match)) {
		$string = $match[2];
		return;
	}

	if (substr_count($string, '{$') > 0) {
		$string = preg_replace('/\{\$..\:(.*?)(;\$|;?\})/smi', '\\1', $string);
		$firephp->log($string);
	}
}

function pps_smf_parselanguage(&$user) {
	global $user_info;
	if (!array_key_exists('iso639-1', $user_info)) {
		$lang = strtolower(substr(0, 3, $user['language']));
		switch($lang) {
			case 'ger': $user['iso639-1'] = 'de'; break;
			case 'eng': $user['iso639-1'] = 'en'; break;
			case 'ita': $user['iso639-1'] = 'it'; break;
			case 'fre': $user['iso639-1'] = 'fr'; break;
			default: $user['iso639-1'] = 'en'; break;
		}
		$user_info['iso639-1'] = $user['iso639-1'];
	} else {
		$user['iso639-1'] = $user_info['iso639-1'];
	}
}
