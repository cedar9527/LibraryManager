<?php
namespace io;
/**
 * Interface to wrap PDO db access.
 * Implementors should take care of provider related details such as parameter bindings.
 */
interface IPdoProvider {
  /**
   * Executes a modification query.
   * @param $procedure string
   * @param $parameters a hash in the form paramName => { value => val, type: PDO::PARAM_*}
   * @return int the number of affected rows
   */
  public function exec($procedure, array $parameters);
  /**
   * Executes a selection query.
   * @param $procedure string
   * @param $parameters a hash in the form paramName => { value => val, type: PDO::PARAM_*}
   * @return a PDOStatement.
   */
  public function query($procedure, array $parameters);
}
