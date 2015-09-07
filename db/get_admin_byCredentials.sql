DELIMITER !!
DROP PROCEDURE IF EXISTS get_admin_byCredentials!!
/******************************
 * Description: Selectionne un administrateur de la table `Admin` d'aprés son id.
 * paramètre: login VARCHAR(255)
 * paramètre: mdp VARCHAR(64)
 * résultat:
 *   id INT,
 *   nom VARCHAR(255),
 *   login VARCHAR(255),
 *   mdp VARCHAR(64),
 *   email VARCHAR(400)
 *****************************/
CREATE PROCEDURE get_admin_byCredentials(
  login VARCHAR(255),
  mdp VARCHAR(64)
)
BEGIN
  SELECT `id`, `nom`, `login`, `mdp`, `email`
  FROM `Admin`
  WHERE `Admin`.`login` = login and `Admin`.`mdp` = mdp;
END!!
DELIMITER ;
