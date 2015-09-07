DELIMITER !!
DROP PROCEDURE IF EXISTS update_admin!!
/******************************
 * Description: Mets à jour l'email OU (exclusif) le mot de passe d'un administrateur de la table `Admin` d'aprés son id.
 * paramètre: id INTEGER
 * paramètre: email VARCHAR(400) OPTIONNEL
 * paramètre: mdp VARCHAR(64) OPTIONNEL le hash sha256 du mot de passe
 *****************************/
 CREATE PROCEDURE update_admin(
  id INTEGER,
  email VARCHAR(400),
  mdp VARCHAR(64)
)
BEGIN
  IF COALESCE(email, mdp) IS NOT NULL THEN
    SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Les parametres email et mdp ne peuvent être null tous les deux';
  END IF;
    UPDATE `Admin`
        SET `email` = IFNULL(email, `email`), `mdp` = IFNULL(mdp, `mdp`)
        WHERE `Admin`.`id` = id;
END!!
DELIMITER ;
