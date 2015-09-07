DELIMITER !!
DROP PROCEDURE IF EXISTS update_film!!
/******************************
 * Description: Mets à jour un film de la table `Films` d'aprés son id.
 * paramètre: id INTEGER
 * paramètre: titre VARCHAR(255) OPTIONNEL,
 * paramètre: realisateur VARCHAR(255) OPTIONNEL,
 * paramètre: annee INTEGER OPTIONNEL,
 * paramètre: description VARCHAR(4000) OPTIONNEL,
 * paramètre: contenu VARCHAR(500) OPTIONNEL
 *****************************/
CREATE PROCEDURE update_film(
  id INTEGER,
  titre VARCHAR(255),
  realisateur VARCHAR(255),
  annee INTEGER,
  description VARCHAR(4000),
  contenu VARCHAR(500)
)
BEGIN
  IF COALESCE(id, titre, realisateur, annee, description, contenu) IS NULL THEN
    SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT='Tous les parametres ne peuvent être null en même temps.';
  END IF;
  
  UPDATE `Films`
  SET
    `titre` = IFNULL(titre, `titre`),
    `realisateur` = IFNULL(realisateur, `realisateur`),
    `description` = IFNULL(description, `description`),
    `contenu` = IFNULL(contenu, `contenu`),
    `annee` = IFNULL(annee, `annee`)
  WHERE `Films`.`id` = id;
END!!
DELIMITER ;
