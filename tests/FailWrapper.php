<?php
/**
 * SPDX-FileCopyrightText: 2020 Robin Appelman <robin@icewind.nl>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Icewind\Streams\Tests;

class FailWrapper extends \Icewind\Streams\NullWrapper {
	public static function wrap($source) {
		return self::wrapSource($source);
	}

	public function stream_read($count) {
		return false;
	}

	public function stream_write($data) {
		return false;
	}
}
