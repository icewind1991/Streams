<?php
/**
 * SPDX-FileCopyrightText: 2018 Robin Appelman <robin@icewind.nl>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Icewind\Streams\Tests;

use Icewind\Streams\CountWrapper;

class CountWrapperTest extends WrapperTest {
	protected function wrapSource($source, $callback = null) {
		if (is_null($callback)) {
			$callback = function () {
			};
		}
		return CountWrapper::wrap($source, $callback);
	}

	public function testReadCount() {
		$count = 0;

		$source = fopen('php://temp', 'r+');
		fwrite($source, 'foobar');
		rewind($source);

		$wrapped = CountWrapper::wrap($source, function ($readCount) use (&$count) {
			$count = $readCount;
		});

		stream_get_contents($wrapped);
		fclose($wrapped);
		$this->assertSame(6, $count);
	}

	public function testWriteCount() {
		$count = 0;

		$source = fopen('php://temp', 'r+');

		$wrapped = CountWrapper::wrap($source, function ($readCount, $writeCount) use (&$count) {
			$count = $writeCount;
		});

		fwrite($wrapped, 'foobar');
		fclose($wrapped);
		$this->assertSame(6, $count);
	}
}
