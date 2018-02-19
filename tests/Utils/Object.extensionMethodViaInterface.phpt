<?php

/**
 * Test: Nette\Object extension method via interface.
 */

use Nette\LegacyObject;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


interface IFirst
{
}

interface ISecond extends IFirst
{
}

class TestClass extends LegacyObject implements ISecond
{
	public $foo = 'Hello';

	public $bar = 'World';
}


function IFirst_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}


function ISecond_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}


@LegacyObject::extensionMethod('ISecond::join', 'ISecond_join'); // is deprecated
@LegacyObject::extensionMethod('IFirst::join', 'IFirst_join'); // is deprecated

$obj = new TestClass;
Assert::same('ISecond_join says Hello*World', $obj->join('*'));

Assert::same(
	['join' => 'ISecond_join'],
	@Nette\Utils\ObjectMixin::getExtensionMethods(TestClass::class) // is deprecated
);

Assert::same(
	['join' => 'IFirst_join'],
	@Nette\Utils\ObjectMixin::getExtensionMethods(IFirst::class) // is deprecated
);

Assert::exception(function () {
	$obj = new TestClass;
	$obj->joi();
}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::joi(), did you mean join()?');
