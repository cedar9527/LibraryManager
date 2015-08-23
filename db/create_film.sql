DELIMITER !!
DROP PROCEDURE IF EXISTS create_film!!
/******************************
 * Description: Ajoute un film dans la table `Films`
 * paramètre: id INTEGER OUT
 * paramètre: titre VARCHAR(255)
 * paramètre: realisateur VARCHAR(255)
 * paramètre: annee INTEGER
 * paramètre: description VARCHAR(4000)
 * paramètre: contenu VARCHAR(500)
 *****************************/
CREATE PROCEDURE create_film(
  OUT id INTEGER,
  titre VARCHAR(255),
  realisateur VARCHAR(255),
  annee INTEGER,
  description VARCHAR(4000),
  contenu VARCHAR(500)
)
BEGIN
  INSERT INTO Films (`titre`, `realisateur`, `annee`, `description`, `contenu`)
  VALUE (titre, realisateur, annee, description, contenu);

  SET id = mysql_insert_id();
END!!
DELIMITER ;
