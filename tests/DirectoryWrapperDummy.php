<?php
/**
 * SPDX-FileCopyrightText: 2020 Robin Appelman <robin@icewind.nl>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace Icewind\Streams\Tests;

class DirectoryWrapperDummy extends \Icewind\Streams\DirectoryWrapper {
	public static function wrap($source) {
		return self::wrapSource($source);
	}

	public function dir_readdir() {
		$file = parent::dir_readdir();
		if ($file !== false) {
			$file .= '_';
		}
		return $file;
	}
}
