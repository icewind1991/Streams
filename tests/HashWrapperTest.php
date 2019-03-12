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
			['md5',  '818c6e601a24f72750da0f6c9b8ebe28'],
			['sha1', 'cca0871ecbe200379f0a1e4b46de177e2d62e655'],
			['sha256', '973153f86ec2da1748e63f0cf85b89835b42f8ee8018c549868a1308a19f6ca3'],
			['sha512', '83cd8866be238eda447cb0ee94a6bfa6248109346b1ce3c75f8a67d35f3d8ab1697b46703065c094fcc7d3a61acc1e8ee85a4f306f13cc1a7aea7651781199b3'],
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
