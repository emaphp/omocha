<?php
namespace Omocha;

use Omocha\Fixtures\UserFixture;
use Omocha\Omocha;

/**
 * Facade tests
 * @author emaphp
 */
class AnnotationBagTest extends \PHPUnit_Framework_TestCase {
	private $reflectionClass;
	
	public function setUp() {
		$this->reflectionClass = new \ReflectionClass('Omocha\Fixtures\UserFixture');
	}
	
	public function testAnnotationValues() {
		$reflectionClass = new \ReflectionClass('Omocha\Fixtures\CarFixture');
		$annotationBag = Omocha::getAnnotations($reflectionClass);
		
		$this->assertTrue($annotationBag->has('Toy'));
		$this->assertTrue($annotationBag->get('Toy')->getValue());
		$this->assertNull($annotationBag->get('Toy')->getArgument());
		
		$this->assertTrue($annotationBag->has('Remote'));
		$this->assertFalse($annotationBag->get('Remote')->getValue());
		$this->assertNull($annotationBag->get('Remote')->getArgument());
		
		$this->assertTrue($annotationBag->has('MadeOf'));
		$this->assertEquals("plastic", $annotationBag->get('MadeOf')->getValue());
		$this->assertNull($annotationBag->get('MadeOf')->getArgument());
		
		$this->assertTrue($annotationBag->has('Stock'));
		$this->assertEquals(50, $annotationBag->get('Stock')->getValue());
		$this->assertNull($annotationBag->get('Stock')->getArgument());
		
		$this->assertTrue($annotationBag->has('Price'));
		$this->assertEquals(45.57, $annotationBag->get('Price')->getValue());
		$this->assertNull($annotationBag->get('Price')->getArgument());
		
		$capacity = $reflectionClass->getProperty('capacity');
		$annotationBag = Omocha::getAnnotations($capacity);
		
		$this->assertTrue($annotationBag->has('Passengers'));
		$this->assertNull($annotationBag->get('Passengers')->getArgument());
		$arr = $annotationBag->get('Passengers')->getValue();
		$this->assertInternalType('array', $arr);
		$this->assertCount(3, $arr);
		$this->assertContains('Spiderman', $arr);
		$this->assertContains('Hulk', $arr);
		$this->assertContains('Penguin', $arr);
		
		$manufacturer = $reflectionClass->getProperty('manufacturer');
		$annotationBag = Omocha::getAnnotations($manufacturer);
		
		$this->assertTrue($annotationBag->has('Description'));
		$this->assertNull($annotationBag->get('Description')->getArgument());
		$description = $annotationBag->get('Description')->getValue();
		$this->assertInstanceOf('stdClass', $description);
		$this->assertObjectHasAttribute('name', $description);
		$this->assertEquals('Toy-R-Us', $description->name);
		$this->assertObjectHasAttribute('location', $description);
		$this->assertEquals("US", $description->location);
		$this->assertObjectHasAttribute('employees', $description);
		$this->assertEquals(100, $description->employees);
	}
	
	public function testClassAnnotations() {
		$annotationBag = Omocha::getAnnotations($this->reflectionClass);
		$this->assertInstanceOf('Omocha\AnnotationBag', $annotationBag);
		
		//has
		$this->assertTrue($annotationBag->has('Entity'));
		$this->assertFalse($annotationBag->has('None'));
		
		//get
		$this->assertInstanceOf('Omocha\Annotation', $annotationBag->get('Entity'));
		$this->assertEquals('Entity', $annotationBag->get('Entity')->getName());
		$this->assertEquals('users', $annotationBag->get('Entity')->getValue());
		$this->assertNull($annotationBag->get('Entity')->getArgument());
		$this->assertNull($annotationBag->get('None'));
		
		//find
		$emptyList = $annotationBag->find('None');
		$this->assertCount(0, $emptyList);
		
		$list = $annotationBag->find('Entity');
		$this->assertCount(1, $list);
		$this->assertInstanceOf('Omocha\Annotation', $list[0]);
		$this->assertEquals('Entity', $list[0]->getName());
		$this->assertEquals('users', $list[0]->getValue());
		$this->assertNull($list[0]->getArgument());
		
		//filters
		$list = $annotationBag->find('Entity', Filter::TYPE_STRING);
		$this->assertCount(1, $list);
		
		$list = $annotationBag->find('Entity', Filter::TYPE_ALL);
		$this->assertCount(1, $list);
		
		$list = $annotationBag->find('Entity', Filter::TYPE_STRING | Filter::TYPE_BOOLEAN);
		$this->assertCount(1, $list);
		
		$list = $annotationBag->find('Entity', Filter::TYPE_BOOLEAN);
		$this->assertCount(0, $list);
		
		$list = $annotationBag->find('Entity', Filter::HAS_ARGUMENT);
		$this->assertCount(0, $list);
		
		$list = $annotationBag->find('Entity', Filter::NOT_HAS_ARGUMENT);
		$this->assertCount(1, $list);
		
		$list = $annotationBag->find('Entity', Filter::NOT_HAS_ARGUMENT | Filter::TYPE_STRING);
		$this->assertCount(1, $list);
		
		$list = $annotationBag->find('Entity', Filter::NOT_HAS_ARGUMENT | Filter::TYPE_INTEGER);
		$this->assertCount(0, $list);
		
		$list = $annotationBag->find('Entity', Filter::HAS_ARGUMENT | Filter::TYPE_STRING);
		$this->assertCount(0, $list);
	}
	
	public function testPropertyAnnotations() {
		//id
		$idAnnotations = Omocha::getAnnotations($this->reflectionClass->getProperty('id'));
		$this->assertTrue($idAnnotations->has('Id'));
		$this->assertTrue($idAnnotations->has('Type'));
		$this->assertTrue($idAnnotations->get('Id')->getValue());
		$this->assertEquals('integer', $idAnnotations->get('Type')->getValue());
		
		//name
		$nameAnnotations = Omocha::getAnnotations($this->reflectionClass->getProperty('name'));
		$this->assertTrue($nameAnnotations->has('Column'));
		$this->assertEquals('username', $nameAnnotations->get('Column')->getValue());
		
		//accounts
		$accountsAnnotations = Omocha::getAnnotations($this->reflectionClass->getProperty('accounts'));
		$this->assertTrue($accountsAnnotations->has('Query'));
		$this->assertTrue($accountsAnnotations->has('Option'));
		$this->assertEquals("SELECT * FROM accounts WHERE user_id = #{id}", $accountsAnnotations->get('Query')->getValue());
		$option = $accountsAnnotations->get('Option');
		$this->assertEquals('map.type', $option->getArgument());
		$this->assertEquals('array[]', $option->getValue());
		
		$options = $accountsAnnotations->find('Option');
		$this->assertCount(1, $options);
		
		$options = $accountsAnnotations->find('Option', Filter::HAS_ARGUMENT);
		$this->assertCount(1, $options);
		
		//friends
		$friendsAnnotations = Omocha::getAnnotations($this->reflectionClass->getProperty('friends'));
		$this->assertTrue($friendsAnnotations->has('Query'));
		$this->assertTrue($friendsAnnotations->has('Parameter'));
		$this->assertEquals("SELECT * FROM people WHERE best_friend = %{i} OR name LIKE %{s}", $friendsAnnotations->get('Query')->getValue());
		$this->assertTrue($friendsAnnotations->get('Parameter')->getValue());
		$this->assertTrue($friendsAnnotations->get('Parameter')->hasArgument());
		$this->assertEquals('id', $friendsAnnotations->get('Parameter')->getArgument());
		
		$parameters = $friendsAnnotations->find('Parameter');
		$this->assertCount(2, $parameters);
		
		$parameters = $friendsAnnotations->find('Parameter', Filter::HAS_ARGUMENT);
		$this->assertCount(2, $parameters);
		
		$parameters = $friendsAnnotations->find('Parameter', Filter::NOT_HAS_ARGUMENT);
		$this->assertCount(0, $parameters);
		
		//comments
		$commentsAnnotations = Omocha::getAnnotations($this->reflectionClass->getProperty('comments'));
		$this->assertTrue($commentsAnnotations->has('StatementId'));
		$this->assertEquals('users.findComments', $commentsAnnotations->get('StatementId')->getValue());
		$this->assertTrue($commentsAnnotations->has('Self'));
		$this->assertTrue($commentsAnnotations->has('Parameter'));
		$this->assertTrue($commentsAnnotations->get('Parameter')->getValue());
		$this->assertTrue($commentsAnnotations->has('Option'));
		
		$parameters = $commentsAnnotations->find('Parameter');
		$this->assertCount(1, $parameters);
		
		$parameters = $commentsAnnotations->find('Parameter', Filter::HAS_ARGUMENT);
		$this->assertCount(0, $parameters);
		
		$parameters = $commentsAnnotations->find('Parameter', Filter::NOT_HAS_ARGUMENT);
		$this->assertCount(1, $parameters);
		
		$options = $commentsAnnotations->find('Option', Filter::HAS_ARGUMENT);
		$this->assertCount(0, $options);
		
		//message
		$messageAnnotations = Omocha::getAnnotations($this->reflectionClass->getProperty('message'));
		$this->assertTrue($messageAnnotations->has('Eval'));
		$this->assertEquals("(. (#name) \" has a good karma\")", $messageAnnotations->get('Eval')->getValue());
		
		//chatter
		$chatterAnnotations = Omocha::getAnnotations($this->reflectionClass->getProperty('chatter'));
		$this->assertTrue($chatterAnnotations->has('If'));
		$this->assertEquals("(> (count (#comments)) 10)", $chatterAnnotations->get('If')->getValue());
		$this->assertTrue($chatterAnnotations->has('StatementId'));
		$this->assertEquals("users.getLastComments", $chatterAnnotations->get('StatementId')->getValue());
		$this->assertTrue($chatterAnnotations->has('Parameter'));
		$this->assertEquals(10, $chatterAnnotations->get('Parameter')->getValue());
		
		//bestContributions
		$contributionsAnnotations = Omocha::getAnnotations($this->reflectionClass->getProperty('bestContributions'));
		$this->assertTrue($contributionsAnnotations->has('Query'));
		$this->assertEquals("SELECT * FROM comments WHERE user_id = %{i} AND value = %{f}", $contributionsAnnotations->get('Query')->getValue());
		$this->assertTrue($contributionsAnnotations->has('Parameter'));
		$this->assertTrue($contributionsAnnotations->get('Parameter')->getValue());
		$this->assertEquals('id', $contributionsAnnotations->get('Parameter')->getArgument());
		
		$parameters = $contributionsAnnotations->find('Parameter');
		$this->assertCount(2, $parameters);
		
		$parameters = $contributionsAnnotations->find('Parameter', Filter::HAS_ARGUMENT);
		$this->assertCount(1, $parameters);
		
		$parameters = $contributionsAnnotations->find('Parameter', Filter::NOT_HAS_ARGUMENT);
		$this->assertCount(1, $parameters);
		
		$parameters = $contributionsAnnotations->find('Parameter', Filter::TYPE_FLOAT);
		$this->assertCount(1, $parameters);
		$this->assertEquals(7.5, $parameters[0]->getValue());
	}
}
?>