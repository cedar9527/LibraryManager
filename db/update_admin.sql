DELIMITER !!
DROP PROCEDURE IF EXISTS update_admin!!
/******************************
 * Description: Mets à jour l'email OU (exclusif) le mot de passe d'un administrateur de la table `Admin` d'aprés son id.
 * paramètre: id INTEGER
 * paramètre: email VARCHAR(400)
 * paramètre: mdp VARCHAR(64) le hash sha256 du mot de passe
 *****************************/
 CREATE PROCEDURE update_admin(
  id INTEGER,
  email VARCHAR(400),
  mdp VARCHAR(64)
)
BEGIN
  UPDATE `Admin`
    SET `email` = email, `mdp` = mdp
  WHERE `Admin`.`id` = id;
END!!
DELIMITER ;
