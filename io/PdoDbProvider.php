<?php
interface IPdoDbProvider {
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
   * @return an \io\MySqlResultSet.
   */
  public function query($procedure, array $parameters);
}
