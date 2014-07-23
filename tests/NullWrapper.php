<?php
/**
 * Copyright (c) 2014 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Streams\Tests;

class NullWrapperTest extends Wrapper {

	public function setUp() {
		stream_wrapper_register('null', '\Icewind\Streams\NullWrapper');
	}

	public function tearDown() {
		stream_wrapper_unregister('null');
	}

	/**
	 * @param resource $source
	 * @return resource
	 */
	protected function wrapSource($source) {
		$context = stream_context_create(array(
			'null' => array(
				'source' => $source
			)
		));
		return fopen('null://', 'r+', false, $context);
	}

	/**
	 * @expectedException \BadMethodCallException
	 */
	public function testNoContext() {
		$context = stream_context_create(array());
		fopen('null://', 'r+', false, $context);
	}

	/**
	 * @expectedException \BadMethodCallException
	 */
	public function testNoSource() {
		$context = stream_context_create(array(
			'null' => array(
				'source' => 'bar'
			)
		));
		fopen('null://', 'r+', false, $context);
	}
}
