DELIMITER !!
DROP PROCEDURE IF EXISTS get_admin!!
/******************************
 * /!\ Inutilisee /!\
 * Description: Selectionne un administrateur de la table `Admin` d'aprés son id.
 * paramètre: id INTEGER
 * résultat:
 *   id INT,
 *   nom VARCHAR(255),
 *   login VARCHAR(255),
 *   mdp VARCHAR(64),
 *   email VARCHAR(400)
 *****************************/
CREATE PROCEDURE get_admin(
  id INTEGER
)
BEGIN
  SELECT `id`, `nom`, `login`, `mdp`, `email`
  FROM `Admin`
  WHERE `Admin`.`id` = id;
END!!
DELIMITER ;
