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
 
class SXE_TestCase_lastChild extends PHPUnit_Framework_TestCase
{
	public function testChild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$child3 = $root->lastChild();

		$this->assertXmlStringEqualsXmlString($child3->asXML(), $root->child3->asXML());
	}

	public function testGrandchild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3><grandchild /></child3></root>');
		$grandchild = $root->child3->lastChild();

		$this->assertXmlStringEqualsXmlString(
			$grandchild->asXML(),
			$root->child3->grandchild->asXML()
		);
	}

	public function testNoChild()
	{
		$root = new SXE('<root />');
		$this->assertNull($root->lastChild());
	}

	public function testNoGrandchild()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$grandchild = $root->child3->lastChild();

		$this->assertNull($grandchild);
	}

	public function testReturn()
	{
		$root = new SXE('<root><child1 /><child2 /><child3 /></root>');
		$child3 = $root->lastChild();

		$this->assertTrue($child3 instanceof SXE);
	}
}