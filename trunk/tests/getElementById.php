<?php
/*

Copyright 2007 The SXE Working Group Initiative

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../sxe.php';
 
class SXE_TestCase_getElementById extends PHPUnit_Framework_TestCase
{
	public function testChild()
	{
		$root = new SXE(
			'<root>
				<child1 id="foo" />
				<child2 id="bar" />
				<child3 id="baz">
					<grandchild id="quux" />
				</child3>
			</root>'
		);

		$expected_return = clone $root->child2;
		$return = $root->getElementById('bar');

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $expected_return->asXML());
	}

	public function testGrandchild()
	{
		$root = new SXE(
			'<root>
				<child1 id="foo" />
				<child2 id="bar" />
				<child3 id="baz">
					<grandchild id="quux" />
				</child3>
			</root>'
		);

		$expected_return = clone $root->child3->grandchild;
		$return = $root->getElementById('quux');

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $expected_return->asXML());
	}

	public function testNotFound()
	{
		$root = new SXE(
			'<root>
				<child1 id="foo" />
				<child2 id="bar" />
				<child3 id="baz">
					<grandchild id="quux" />
				</child3>
			</root>'
		);

		$return = $root->getElementById('inexistent');

		$this->assertFalse($return);
	}

	public function testDupe()
	{
		$root = new SXE(
			'<root>
				<child1 id="foo" />
				<child2 id="foo" />
			</root>'
		);

		$expected_return = clone $root->child1;
		$return = $root->getElementById('foo');

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $expected_return->asXML());
	}

	public function testInvalidArgumentType()
	{
		$root = new SXE('<root />');

		try
		{
			$root->getElementById(false);
			$fail = true;
		}
		catch (Exception $e)
		{
			$fail = false;
		}

		if ($fail)
		{
			self::fail();
		}
	}
}