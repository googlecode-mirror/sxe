<?php
/*

Copyright 2007 The SXE Working Group Initiative

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

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
		catch (InvalidArgumentException $e)
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
		catch (InvalidArgumentException $e)
		{
			$fail = false;
		}

		if ($fail)
		{
			self::fail();
		}
	}
}