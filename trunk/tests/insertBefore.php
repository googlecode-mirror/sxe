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
 
class SXE_TestCase_insertBefore extends PHPUnit_Framework_TestCase
{
	public function testBeforeFirstChild()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$root->insertBefore($new, $root->child);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><new /><child /></root>');
	}

	public function testBeforeLastChild()
	{
		$root = new SXE('<root><child /><otherchild /></root>');
		$new = new SXE('<new />');

		$root->insertBefore($new, $root->otherchild);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><child /><new /><otherchild /></root>');
	}

	public function testNotFound()
	{
		$root = new SXE('<root><child><grandchild /></child></root>');
		$new = new SXE('<new />');

		try
		{
			$root->insertBefore($new, $root->child->grandchild);
		}
		catch (DOMException $e)
		{
			$this->assertSame($e->code, DOM_NOT_FOUND_ERR);
		}
		catch (Exception $e)
		{
			self::fail('Unexpected exception thrown: ' . get_class($e) . '(' . $e->getMessage() . ')');
		}

		if (!isset($e))
		{
			self::fail('No exception thrown');
		}
	}

	public function testNoRef()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$root->insertBefore($new);

		$this->assertXmlStringEqualsXmlString($root->asXML(), '<root><child /><new /></root>');
	}

	public function testReturn()
	{
		$root = new SXE('<root><child /></root>');
		$new = new SXE('<new />');

		$return = $root->insertBefore($new, $root->child);

		$this->assertTrue($return instanceof SXE);
		$this->assertXmlStringEqualsXmlString($return->asXML(), $new->asXML());
	}
}