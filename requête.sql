-- Afficher les sessions de formation à venir qui se chevauchent pas avec une session donnée
SELECT * FROM Session
WHERE id_session <> $_SESSION_ID
AND (date_debut BETWEEN '$DATE_DEBUT' AND '$DATE_FIN'
OR date_fin BETWEEN '$DATE_DEBUT' AND '$DATE_FIN');

SELECT * FROM Session
WHERE id_formation = [id_formation] 
  AND date_debut > [date_fin] OR date_fin < [date_debut]



-- Afficher les sessions de formation à venir avec des places encore disponibles
SELECT * FROM Session
WHERE nombre_de_places > (SELECT COUNT(*) FROM Evaluation JOIN Apprenant ON Apprenant.id_apprenant=Evaluation.id_apprenant
WHERE id_session=$_SESSION_ID);

-- Afficher le nombre des inscrits par session de formation
SELECT session_formation, COUNT(inscrit_formation) AS nb_inscrits
FROM table_inscriptions
GROUP BY session_formation

-- Afficher l'historique des sessions de formation d'un apprenant donné
SELECT * FROM Session
JOIN Evaluation ON Session.id_session=Evaluation.id_session
WHERE Evaluation.id_apprenant=$_APPRENANT_ID;

-- Afficher la liste des sessions qui sont affectées à un formateur donné, triées par date de début
SELECT * FROM Session
WHERE id_formateur=$_FORMATEUR_ID
ORDER BY date_debut;

-- Afficher la liste des apprenants d'une session donnée d'un formateur donné
SELECT * FROM Apprenant
JOIN Evaluation ON Apprenant.id_apprenant=Evaluation.id_apprenant
WHERE id_session=$_SESSION_ID
AND id_formateur=$_FORMATEUR_ID;

-- Afficher l'historique des sessions de formation d'un formateur donné
SELECT * FROM Session
WHERE id_formateur=$_FORMATEUR_ID;

-- Afficher les formateurs qui sont disponibles entre 2 dates
SELECT * FROM Formateur
WHERE id_formateur NOT IN
(SELECT id_formateur FROM Session
WHERE '$DATE_DEBUT' BETWEEN date_debut AND date_fin
OR '$DATE_FIN' BETWEEN date_debut AND date_fin);

-- Afficher toutes les sessions d'une formation donnée
SELECT * FROM Session
WHERE id_formation=$_FORMATION_ID;

-- Afficher le nombre total des sessions par cétegorie de formation
SELECT categorie, COUNT(*) AS nombre_sessions
FROM Formation
GROUP BY categorie;

-- Afficher le nombre total des inscrits par catégorie formation
SELECT categorie, SUM(nombre_inscrits) AS nombre_inscrits_total
FROM
(SELECT Session.id_session, COUNT(Evaluation.id_session) AS nombre_inscrits, Formation.categorie
FROM Session
JOIN Formation ON Session.id_formation=Formation.id_formation
LEFT JOIN Evaluation ON Session.id_session=Evaluation.id_session
GROUP BY Session.id_session, Formation.categorie) ASnb_inscrits
GROUP BY categorie;