<?php
namespace Omocha;

class ValueParser {
	/**
	 * Parses an annotation value
	 * @param string $value
	 * @return mixed
	 */
	public function parse($value) {
		$json = $this->decode($value);
		if (JSON_ERROR_NONE == json_last_error()) {
			return $json;
		}
		$float = filter_var($value, FILTER_VALIDATE_FLOAT);
		return $float !== false ? $float : $value;
	}
	
	/**
	 * Decodes a JSON string
	 * @param unknown $value
	 * @return mixed
	 */
	protected function decode($value) {
		if (defined('JSON_PARSER_NOTSTRICT')) {
			return json_decode($value, false, 512, JSON_PARSER_NOTSTRICT);
		}
		return json_decode($value);
	}
}
?>