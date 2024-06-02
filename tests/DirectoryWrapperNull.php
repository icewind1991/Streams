<?php
/**
 * SPDX-FileCopyrightText: 2020 Robin Appelman <robin@icewind.nl>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Icewind\Streams\Tests;

class DirectoryWrapperNull extends \Icewind\Streams\DirectoryWrapper {
	public static function wrap($source) {
		return self::wrapSource($source);
	}
}
