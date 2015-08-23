DELIMITER !!
DROP PROCEDURE IF EXISTS delete_film!!
/******************************
 * Description: Supprime un fim de la table `Films`
 * paramètre: id INTEGER
 *****************************/
CREATE PROCEDURE delete_film(
  id INTEGER
)
BEGIN
  DELETE
  FROM `Films`
  WHERE `Films`.`id` = id;
END!!
DELIMITER ;
