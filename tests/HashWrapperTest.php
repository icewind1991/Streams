<?php
/**
 * @copyright Copyright (c) 2019, Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Icewind\Streams\Tests;

class HashWrapperTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @param resource $source
	 * @param string $hash
	 * @param callable $callback
	 * @return resource
	 */
	protected function wrapSource($source, $hash, $callback) {
		return \Icewind\Streams\HashWrapper::wrap($source, $hash, $callback);
	}

	protected function getSource() {
		$source = fopen('php://temp', 'w+');
		fwrite($source, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
		fseek($source, 0);
		return $source;
	}

	public function hashData() {
		return [
			['md5',  'a8ae2f4a56baf78845c041c833946d00'],
			['sha1', 'f0fd7dbb3fae612002143d51f763b50b4f23bc56'],
			['sha256', '88fe62d4432873edfe147f9d950dc3a78d50de0e71be8a44f5f13d6ad11bced6'],
			['sha512', 'e0e7794d6d7e011ccf4e869b796f3cc80425c6bd6d02118b861a18aabb36b77c15cc6368e1c5cf84338bc0592092d08c670a6f099fce5067cb2a910d3b3f06fd'],
		];
	}

	/**
	 * @dataProvider hashData
	 *
	 * @param string $algorithm
	 * @param string $expectedHash
	 */
	public function testHash($algorithm, $expectedHash) {
		$obtainedHash = null;
		$callback = function($hash) use (&$obtainedHash) {
			$obtainedHash = $hash;
		};

		$stream = $this->wrapSource($this->getSource(), $algorithm, $callback);
		while(feof($stream) === false) {
			fread($stream, 20);
		}
		fclose($stream);

		$this->assertSame($expectedHash, $obtainedHash);
	}
}
