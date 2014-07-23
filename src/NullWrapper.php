<?php
/**
 * Copyright (c) 2014 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Licensed under the MIT license:
 * http://opensource.org/licenses/MIT
 */

namespace Icewind\Streams;

class NullWrapper extends Wrapper {
	public function stream_open() {
		$this->loadContext('null');
		return true;
	}
}
