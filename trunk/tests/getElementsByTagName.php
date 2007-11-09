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
 
class SXE_TestCase_getElementsByTagName extends PHPUnit_Framework_TestCase
{
	public function test()
	{
		$root = new SXE(
			'<root>
				<tag id="foo" />
				<othertag>
					<tag id="bar" />
				</othertag>
				<tag id="baz" />
			</root>'
		);

		$expected_return = array(
			clone $root->tag[0],
			clone $root->othertag->tag,
			clone $root->tag[1]
		);

		$return = $root->getElementsByTagName('tag');

		$this->assertEquals($return, $expected_return);
	}

	public function testNS()
	{
		$root = new SXE(
			'<root xmlns:xxx="urn:xxx">
				<xxx:tag id="foo" />
				<othertag>
					<xxx:tag id="bar" />
				</othertag>
				<tag id="baz" />
			</root>'
		);

		$expected_return = array(
			new SXE('<xxx:tag id="foo" xmlns:xxx="urn:xxx" />'),
			new SXE('<xxx:tag id="bar" xmlns:xxx="urn:xxx" />')
		);

		$return = $root->getElementsByTagName('xxx:tag');

		$this->assertEquals($return, $expected_return);
	}

	public function testNotFound()
	{
		$root = new SXE('<root />');

		$expected_return = array();
		$return = $root->getElementsByTagName('inexistent');

		$this->assertEquals($return, $expected_return);
	}

	public function testInvalidArgumentType()
	{
		$root = new SXE('<root />');

		try
		{
			$root->getElementsByTagName(false);
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

	public function testInvalidTagName()
	{
		$root = new SXE('<root />');

		try
		{
			$root->getElementsByTagName('$$$$');
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