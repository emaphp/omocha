<?php
namespace Omocha;

class AnnotationBag implements \IteratorAggregate, \Countable, \JsonSerializable {
	/**
	 * Annotation list
	 * @var array
	 */
	protected $annotations = [];
	
	/**
	 * Annotation names
	 * @var array
	 */
	protected $names = [];
	
	public function __construct(array $annotations) {
		$this->replace($annotations);
	}
	
	/**
	 * Replaces a set of annotations values
	 * @param array $annotations
	 */
	public function replace(array $annotations) {
		$this->annotations = $annotations;
		$this->names = [];
		$index = 0;
		
		foreach ($annotations as $annotation) {
			$name = $annotation->getName();
			if (!array_key_exists($name, $this->names)) {
				$this->names[$name] = [];
			}
			$this->names[$name][] = $index++;
		}
	}
	
	/**
	 * Unbox all annotations in the form of an associative array
	 * @return array
	 */
	public function export() {
		return $this->annotations;
	}
	
	/**
	 * Checks if a given annotation is available
	 * @param atring $key
	 * @return boolean
	 */
	public function has($key) {
		return array_key_exists($key, $this->names);
	}
	
	/**
	 * Retrieves a single annotation
	 * @param string $key
	 * @return array|NULL
	 */
	public function get($key) {
		if ($this->has($key)) {
			return $this->annotations[$this->names[$key][0]];
		}
		return null;
	}
	
	/**
	 * Retrieves a list of annotations by name
	 * @param string $key
	 * @param int $filter
	 * @return array
	 */
	public function find($key, $filter = null) {
		if (!$this->has($key)) {
			return [];
		}
		
		$annotations = [];
		
		foreach ($this->names[$key] as $index) {
			if (!is_int($filter)) {
				$annotations[] = $this->annotations[$index];
				continue;
			}
			
			$annotation = $this->annotations[$index];
			$flags = 0;
			
			//match value type
			if ($filter & Filter::TYPE_ALL) {
				$value = $annotation->getValue();
				if (is_string($value)) {
					$flags |= Filter::TYPE_STRING;
				}
				elseif (is_int($value)) {
					$flags |= Filter::TYPE_INTEGER;
				}
				elseif (is_float($value)) {
					$flags |= Filter::TYPE_FLOAT;
				}
				elseif (is_bool($value)) {
					$flags |= Filter::TYPE_BOOLEAN;
				}
				elseif (is_array($value)) {
					$flags |= Filter::TYPE_ARRAY;
				}
				elseif (id_object($value)) {
					$flags |= Filter::TYPE_OBJECT;
				}
			}
			
			//match argument filter
			if ($filter & Filter::HAS_ARGUMENT || $filter & Filter::NOT_HAS_ARGUMENT) {
				if ($annotation->getArgument()) {
					$flags |= Filter::HAS_ARGUMENT;
				}
				else {
					$flags |= Filter::NOT_HAS_ARGUMENT;
				}
			}
			
			//add annotation if it meets all requirements
			if (($flags & $filter) == $flags) {
				$annotations[] = $annotation;
			}
		}
		
		return $annotations;
	}
	
	public function count() {
		return count($this->annotations);
	}
	
	public function jsonSerialize() {
		return $this->export();
	}
	
	public function getIterator() {
		return new \ArrayIterator($this->annotations);
	}
}
?>