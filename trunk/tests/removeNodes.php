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
 
class SXE_TestCase_removeNodes extends PHPUnit_Framework_TestCase
{
	public function testRootContext()
	{
		$xpath = '//*[@remove="1"]';

		$root = new SXE(
			'<root>
				<child1 remove="1" />
				<child2 remove="0" />
				<child3>
					<grandchild remove="1" />
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_result = new SXE(
			'<root>
				<child2 remove="0" />
				<child3 />
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_return = array(
			clone $root->child1,
			clone $root->child3->grandchild
		);

		$return = $root->removeNodes($xpath);

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_result->asXML());
		$this->assertEquals($return, $expected_return);
	}

	public function testChildContext()
	{
		$xpath = './/*[@remove="1"]';

		$root = new SXE(
			'<root>
				<child1 remove="1" />
				<child2 remove="0" />
				<child3>
					<grandchild>
						<grandgrandchild remove="1" />
					</grandchild>
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_result = new SXE(
			'<root>
				<child1 remove="1" />
				<child2 remove="0" />
				<child3>
					<grandchild />
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_return = array(
			clone $root->child3->grandchild->grandgrandchild
		);

		$return = $root->child3->removeNodes($xpath);

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_result->asXML());
		$this->assertEquals($return, $expected_return);
	}

	public function testChildContextNoMatches()
	{
		$xpath = './*[@remove="1"]';

		$root = new SXE(
			'<root>
				<child1 remove="1" />
				<child2 remove="0" />
				<child3>
					<grandchild>
						<grandgrandchild remove="1" />
					</grandchild>
				</child3>
			</root>',

			LIBXML_NOBLANKS
		);

		$expected_result = clone $root;
		$expected_return = array();

		$return = $root->child3->removeNodes($xpath);

		$this->assertXmlStringEqualsXmlString($root->asXML(), $expected_result->asXML());
		$this->assertEquals($return, $expected_return);
	}

	public function testInvalidArgumentType()
	{
		$root = new SXE('<root />');
		$new = new SXE('<new />');

		try
		{
			$root->removeNodes(false, $new);
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