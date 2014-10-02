<?php
namespace Omocha;

class Annotation {
	/**
	 * Annotation name
	 * @var string
	 */
	protected $name;
	
	/**
	 * Annotation argument
	 * @var string
	 */
	protected $argument;
	
	/**
	 * Annotation value
	 * @var mixed
	 */
	protected $value;
	
	public function __construct($name, $value, $argument = null) {
		$this->name = $name;
		$this->value = $value;
		$this->argument = $argument;
	}
	/**
	 * Returns annotation name
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Returns annotation value
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * Tells if the annotation receives an argument
	 * @return boolean
	 */
	public function hasArgument() {
		return isset($this->argument);
	}
	
	/**
	 * Returns annotation argument
	 * @return string
	 */
	public function getArgument() {
		return $this->argument;
	}
}
?>