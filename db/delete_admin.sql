DELIMITER !!
DROP PROCEDURE IF EXISTS delete_admin!!
/******************************
 * Description: Supprime un administrateur de la table `Admin`
 * paramètre: id INTEGER
 *****************************/
 CREATE PROCEDURE delete_admin(
  id INTEGER
)
BEGIN
  DELETE
  FROM `Admin`
  WHERE `Admin`.`id` = id;
END!!
DELIMITER ;
