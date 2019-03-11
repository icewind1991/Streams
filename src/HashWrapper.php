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

namespace Icewind\Streams;

/**
 * Wrapper that calculates the hash on the stream on read
 *
 * The stream and hash should be passed in when wrapping the stream.
 * On close the callback will be called with the calculated checksum.
 *
 * For supported hashes see: http://php.net/manual/en/function.hash-algos.php
 */
class HashWrapper extends Wrapper {

	/**
	 * @var callable
	 */
	private $callback;

	/**
	 * @var resource
	 */
	private $hashContext;

	/**
	 * Wraps a stream to make it seekable
	 *
	 * @param resource $source
	 * @param string $hash
	 * @param callable $callback
	 * @return resource
	 *
	 * @throws \BadMethodCallException
	 */
	public static function wrap($source, $hash, $callback) {
		$context = stream_context_create(array(
			'hash' => $hash,
			'callback' => $callback,
		));
		return self::wrapSource($source, $context);
	}

	public function dir_opendir($path, $options) {
		return false;
	}

	public function stream_open($path, $mode, $options, &$opened_path) {
		$context = $this->loadContext();
		$this->callback = $context['callback'];
		$this->hashContext = hash_init($context['hash']);
		return true;
	}

	public function stream_read($count) {
		$data = parent::stream_read($count);
		if ($this->hashContext !== false) {
			hash_update($this->hashContext, $data);
		}
		return $data;
	}

	public function stream_close() {
		$hash = hash_final($this->hashContext);
		if ($this->hashContext !== false && is_callable($this->callback)) {
			call_user_func($this->callback, $hash);
		}
		return parent::stream_close();
	}
}
