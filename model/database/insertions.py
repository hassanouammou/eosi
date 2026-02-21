import random
from datetime import date, timedelta

# Divisions et Services marocains réalistes
divisions = [
    "Division de l'action sociale",
    "Division des affaires rurales",
    "Division des équipements",
    "Division des collectivités locales",
    "Division du budget et marches",
    "Division des affaires interieures"
]

services = [
    "Service juridique et du contentieux",
    "Service du Budget",
    "Service de la formation continue",
    "Service des terres collectives",
    "Service de la logistique et des archives",
    "Service de l'environnement"
]

# Sujets variés pour Outgoing et Incoming
subjects_outgoing = [
    "Demande de financement pour projets sociaux",
    "Rapport sur l'etat des terres agricoles",
    "Validation du budget annuel",
    "Demande de materiel informatique",
    "Plan de formation pour les employes",
    "Consultation sur nouvelles lois",
    "Projet de sensibilisation environnementale",
    "Demande de cartographie des terres",
    "Suivi des depenses mensuelles",
    "Inventaire annuel du materiel",
    "Rapport sur les projets locaux",
    "Analyse des depenses departementales",
    "Programme de stages pour jeunes",
    "Mise a jour du cadastre",
    "Previsions budgetaires",
    "Demande d'achat de vehicules",
    "Plan de formation continue",
    "Revision reglementaire",
    "Campagne de sensibilisation au recyclage",
    "Nouvelles directives sur l'irrigation"
]

subjects_incoming = [
    "Rapport de formation trimestriel",
    "Carte des parcelles mises a jour",
    "Synthese des depenses mensuelles",
    "Inventaire complet du materiel",
    "Etat d'avancement des formations",
    "Analyse de conformite legale",
    "Rapport sur les campagnes ecologiques",
    "Rapport sur la regularisation fonciere",
    "Depenses prevues vs realisees",
    "Mise a jour de l'inventaire materiel",
    "Nouvelles directives legales locales",
    "Tableau comparatif des depenses",
    "Programme de stages mis a jour",
    "Rapport cadastre",
    "Previsions budgetaires valides",
    "Livraison vehicules recue",
    "Formation continue des employes",
    "Nouvelles lois publiees",
    "Etat des campagnes ecologiques",
    "Directive irrigation"
]

# Fonction pour générer une date aléatoire
def random_date(start_year=2015, end_year=2024):
    start = date(start_year, 1, 1)
    end = date(end_year, 12, 31)
    delta = end - start
    random_days = random.randint(0, delta.days)
    return start + timedelta(days=random_days)

# Fonction utilitaire pour échapper les apostrophes pour PostgreSQL
def escape_pg(text):
    return text.replace("'", "''")

# Génération des INSERT SQL
num_mails_per_year = 20  # Pour chaque année
years = range(2015, 2025) # 2015 à 2024

with open("mails.sql", "w", encoding="utf-8") as f:
    
    # --- OUTGOING MAILS ---
    f.write("-- INSERTS OutgoingMail\n")
    # Ajout de l'entête de l'instruction (Ajustez les noms de colonnes selon votre schéma réel)
    f.write("INSERT INTO OutgoingMail (transmitter, receiver, number, subject, tdate, filename) VALUES\n")
    
    outgoing_values = []
    for year in years:
        for i in range(num_mails_per_year):
            transmitter = escape_pg(random.choice(divisions))
            receiver = escape_pg(random.choice(services))
            number = f"OM{year}C{str(i+1).zfill(3)}"
            subject = escape_pg(random.choice(subjects_outgoing))
            tdate = random_date(year, year)
            filename = f"outgoing-mail-{number}.png"
            
            outgoing_values.append(f"('{transmitter}', '{receiver}', '{number}', '{subject}', '{tdate}', '{filename}')")
    
    # Joindre toutes les valeurs avec une virgule, et terminer par un point-virgule
    f.write(",\n".join(outgoing_values) + ";\n\n")

    # --- INCOMING MAILS ---
    f.write("-- INSERTS IncomingMail\n")
    # Ajout de l'entête de l'instruction (Ajustez les noms de colonnes selon votre schéma réel)
    f.write("INSERT INTO IncomingMail (transmitter, receiver, number, subject, tdate, filename) VALUES\n")
    
    incoming_values = []
    for year in years:
        for i in range(num_mails_per_year):
            transmitter = escape_pg(random.choice(services))
            receiver = escape_pg(random.choice(divisions))
            number = f"IM{year}C{str(i+1).zfill(3)}"
            subject = escape_pg(random.choice(subjects_incoming))
            tdate = random_date(year, year)
            filename = f"incoming-mail-{number}.png"
            
            incoming_values.append(f"('{transmitter}', '{receiver}', '{number}', '{subject}', '{tdate}', '{filename}')")

    # Joindre toutes les valeurs avec une virgule, et terminer par un point-virgule
    f.write(",\n".join(incoming_values) + ";\n")

print("Script SQL généré avec succès pour PostgreSQL dans mails.sql !")