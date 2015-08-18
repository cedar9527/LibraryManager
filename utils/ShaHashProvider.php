<?php
namespace utils;
class ShaHashProvider implements IHashProvider {
  /**
   * Gives a string's hash.
   * @param $data string the data to be hashed
   * @return string the hash
   */
  public function hash($data) {
    return hash('sha256', $data)
  }
}
