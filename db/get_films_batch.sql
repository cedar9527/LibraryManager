DELIMITER !!
DROP PROCEDURE IF EXISTS get_films_batch!!
/******************************
 * Description: Selectionne un ensemble de films dans la table `Films`.
 * paramètre: ignorePremiers INTEGER
 * paramètre: tailleBloc INTEGER
 * résultat:
 *   id INT,
 *   titre VARCHAR(255),
 *   realisateur VARCHAR(255),
 *   annee INTEGER,
 *   description VARCHAR(4000),
 *   contenu VARCHAR(500)
 *****************************/
CREATE PROCEDURE get_films_batch(
  ignorePremiers INTEGER,
  tailleBloc INTEGER
)
BEGIN
  DECLARE start INTEGER;
  SET @start = ignorePremiers + 1;
  SELECT `id`, `titre`, `realisateur`, `annee`, `description`, `contenu`
  FROM `Films`
  LIMIT start, tailleBloc;
END!!
# MySQL returned an empty result set (i.e. zero rows).

DELIMITER ;
