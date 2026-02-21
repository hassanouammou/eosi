Utilisateur(id, email, mot_de_passe, numéro_de_téléphone, rôle)

Administrateur(id, prénom, nom, date_de_naissance, genre, nom_de_photo, #utilisateur_id)

Division(id, nom, #utilisateur_id)

Employé(id, prénom, nom, date_de_naissance, genre, nom_de_photo, division_id, #utilisateur_id)

CourrierDeDépart(id, #courrier_id)

CourrieÀArrivée(id, #courrier_id)

Courrier(id, émetteur, destinataire, numéro, sujet, date_de_transmission, nom_du_courriel)

Service(id, nom)

Notification(id, émetteur, destinataire, sujet, topic_id, créé_le)

AuthentificationUtilisateur(id, email, mot_de_passe_hashé, début_réinitialisation, début_heure, fin_heure, #utilisateur_id)

