<?php
/**
 * SPDX-FileCopyrightText: 2016 Robin Appelman <icewind@owncloud.com>
 * SPDX-License-Identifier: MIT
 */

namespace Icewind\Streams\Tests;

use PHPUnit\Framework\TestCase;

class PathWrapperTest extends TestCase {
	private function getDataStream($data) {
		$stream = fopen('php://temp', 'w+');
		fwrite($stream, $data);
		rewind($stream);
		return $stream;
	}

	public function testFileGetContents() {
		$data = 'foobar';
		$stream = $this->getDataStream($data);
		$path = \Icewind\Streams\PathWrapper::getPath($stream);
		$this->assertSame($data, file_get_contents($path));
	}
}
