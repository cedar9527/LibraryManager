DELIMITER !!
DROP PROCEDURE IF EXISTS create_admin!!
/******************************
 * Description: Ajoute un administrateur dans la table `Admin`
 * paramètre: id INTEGER OUT
 * paramètre: nom VARCHAR(255)
 * paramètre: login VARCHAR(255)
 * paramètre: mdp VARCHAR(64)
 * paramètre: email VARCHAR(400)
 *****************************/
CREATE PROCEDURE create_admin(
  OUT id INTEGER,
  nom VARCHAR(255),
  login VARCHAR(255),
  mdp VARCHAR(64),
  email VARCHAR(400)
)
BEGIN
  INSERT INTO `Admin` (`nom`, `login`, `mdp`, `email`)
  VALUES (nom, login, mdp, email);

  SET id = mysql_insert_id();
END!!
DELIMITER ;
