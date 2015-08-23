DELIMITER !!
DROP PROCEDURE IF EXISTS get_film!!
/******************************
 * /!\ Inutilisee /!\
 * Description: Selectionne un film de la table `Films` d'aprés son id.
 * paramètre: id INTEGER
 * résultat:
 *   id INT,
 *   titre VARCHAR(255),
 *   realisateur VARCHAR(255),
 *   annee INTEGER,
 *   description VARCHAR(4000),
 *   contenu VARCHAR(500)
 *****************************/
 CREATE PROCEDURE get_film(
  id INTEGER
)
BEGIN
  SELECT `id`, `titre`, `realisateur`, `annee`, `description`, `contenu`
  FROM `Films`
  WHERE `Films`.`id` = id;
END!!
