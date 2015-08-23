DELIMITER !!
DROP PROCEDURE IF EXISTS update_film!!
/******************************
 * Description: Mets à jour un film de la table `Films` d'aprés son id.
 * paramètre: id INTEGER
 * paramètre: titre VARCHAR(255),
 * paramètre: realisateur VARCHAR(255),
 * paramètre: annee INTEGER,
 * paramètre: description VARCHAR(4000),
 * paramètre: contenu VARCHAR(500)
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
  UPDATE `Films`
  SET
    `titre` = titre,
    `realisateur` = realisateur,
    `description` = description,
    `contenu` = contenu
  WHERE `Films`.`id` = id;
END!!
DELIMITER ;
