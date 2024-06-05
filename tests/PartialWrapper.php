<?php
/**
 * SPDX-FileCopyrightText: 2020 Robin Appelman <robin@icewind.nl>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Icewind\Streams\Tests;

class PartialWrapper extends \Icewind\Streams\NullWrapper {
	public static function wrap($source) {
		return self::wrapSource($source);
	}

	public function stream_read($count) {
		$count = min($count, 2); // return as most 2 bytes
		return parent::stream_read($count);
	}

	public function stream_write($data) {
		$data = substr($data, 0, 2); //write as most 2 bytes
		return parent::stream_write($data);
	}
}
