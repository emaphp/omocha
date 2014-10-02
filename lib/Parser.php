<?php
namespace Omocha;

class Parser {
	/**
	 * Annotation pattern
	 * @var string
	 */
	const ANNOTATION_PATTERN = '/(?<=\\@)([a-zA-Z\_\-\\\][a-zA-Z0-9\_\-\.\\\]*)(\([a-zA-Z0-9:\_\-\.\\\]*\))?(((?!\s\\@).)*)/s';
	
	/**
	 * Doc comment to parse
	 * @var string
	 */
	protected $rawDocComment;
	
	/**
	 * Annotation value parser
	 * @var ValueParser
	 */
	protected $valueParser;
	
	public function __construct($rawDocComment) {
		$this->rawDocComment = preg_replace('/^\s*\*\s{0,1}|\/\*{1,2}|\s*\*\//m', '', $rawDocComment);
		$this->valueParser = new ValueParser();
	}
	
	/**
	 * Obtains the list of annotations in a doc comment
	 * @return array
	 */
	public function parse() {
		$annotations = [];
		preg_match_all(self::ANNOTATION_PATTERN, $this->rawDocComment, $found);
		foreach ($found[1] as $key => $value) {
			$arg = null;
			if (!empty($found[2][$key])) {
				$arg = substr(trim($found[2][$key]), 1, -1);
			}
			$annotations[] = new Annotation($value, $this->parseValue($found[3][$key]), $arg);
		}
		return $annotations;
	}

	/**
	 * Parses an annotation value
	 * @param string $value
	 * @return mixed
	 */
	protected function parseValue($value) {
		$value = trim($value);
		if ('' == $value) {
			//return as boolean
			return true;
		}
		return $this->valueParser->parse($value); 
	}
}
?>