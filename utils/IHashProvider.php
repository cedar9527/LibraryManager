<?php
namespace utils;
/**
 * Interface for hash providers.
 */
interface IHashProvider {
  /**
   * Gives a string's hash.
   * @param $data string the data to be hashed
   * @return string the hash
   */
  public function hash($data);
}
