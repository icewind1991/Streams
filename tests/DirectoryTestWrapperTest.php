<?php
/**
 * SPDX-FileCopyrightText: 2015 Robin Appelman <icewind@owncloud.com>
 * SPDX-License-Identifier: MIT
 */

namespace Icewind\Streams\Tests;

class DirectoryTestWrapperTest extends IteratorDirectoryTest {

	/**
	 * @param \Iterator | array $source
	 * @return resource
	 */
	protected function wrapSource($source) {
		$dir = \Icewind\Streams\IteratorDirectory::wrap($source);
		return DirectoryWrapperNull::wrap($dir);
	}

	public function testManipulateContent() {
		$source = \Icewind\Streams\IteratorDirectory::wrap(['asd', 'bar']);
		$wrapped = DirectoryWrapperDummy::wrap($source);
		$result = [];
		while (($file = readdir($wrapped)) !== false) {
			$result[] = $file;
		}
		$this->assertEquals(['asd_', 'bar_'], $result);
	}
}
