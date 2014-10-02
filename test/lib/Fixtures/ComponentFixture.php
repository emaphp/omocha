<?php
namespace Omocha\Fixtures;

/**
 * @Type(controller)
 * @Option(output) json
 * @Option(limit) 10
 * @Option(amount) 5.67
 * @Attr(#id) integer
 */
class ComponentFixture {
	/**
	 * @Is(int?)
	 * @Check(!negative)
	 * @PrintAs({{ID}})
	 */
	private $id;
	
	/**
	 * @Higher(25%) false
	 * @Add(+5) total
	 * @Check(size<) 100
	 */
	private $amount;
	
	/**
	 * @Regex(/^ema/)
	 * @Append(.lastName)
	 * @Check(length>) 7
	 */
	private $name;
	
	/**
	 * @Inject($bar) foo
	 * @Return(*string)
	 */
	public function action($foo, $bar) {
		//...
	}
}
?>