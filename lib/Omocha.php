<?php
namespace Omocha;

abstract class Omocha {
	/**
	 * Retrieve annotations from docblock of a given reflector
	 * @param \Reflector $reflector
	 * @return \Omocha\AnnotationBag
	 */
	public static function getAnnotations(\Reflector $reflector) {
		$annotations = (new Parser($reflector->getDocComment()))->parse();
		return new AnnotationBag($annotations);
	}
}
?>