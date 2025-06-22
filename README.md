# Gainia-Vue
Gainia project remade with vue contains both front-end and back-end

## Web markup 2
This is a project made for web markup 2

To start the front end of the project take the following steps:

1. Clone the project
2. open docker desktop
3. navigate to the gainia front-end folder/Gainia
4. Open the terminal in this folder
5. npm install
6. npm run dev (for the dev view)
6. npm run production (for the view without the dev tools)

To start the back end of the project take the following steps:
1. Clone the project
2. open docker desktop
3. navigate to the gainia back-end folder 
4. Open the terminal in this folder
5. docker compose up --build (when starting for the first time)
6. docker compose up (when starting after)



## Login info
### Admin
username: john@example.com
password: P@ssword12345!

### User
username: jane@example.com
password: P@ssword12345!





##Extra's voor moodle
###Required functionality: 
Registratie en inloggen:

1. Account aanmaken, inloggen en uitloggen.

2. Wachtwoorden veilig opslaan.


Trainingslogboek:

1. Gebruikers kunnen kiezen uit een lijst van vooraf toegevoegde oefeningen.

2. De standaard oefeningen kunnen worden toegevoegd, bewerkt of verwijderd worden door een beheerder.

3. Gebruikers kunnen trainingen toevoegen met:

- Oefeningen (bijv. Bench press, Squats, Deadlifts).

- Sets, reps en gewichten.

- Datum en tijd.

4. Mogelijkheid om eigen oefeningen toe te voegen. Deze oefeningen worden dan apart weergegeven en zijn alleen zichtbaar voor de gebruiker. Deze oefening bevat:

- De naam van de oefening.

- De spiergroep die het traint (bijv. Rug, Biceps, Triceps, Borst, cardio, enz…).

- Een optionele omschrijving van de oefening.

Trainingsoverzicht:

1. Een overzicht waar gebruikers hun trainingen kunnen beheren.

2. Herhalende trainingen toevoegen.


Voortgangsmonitoring:

1. Grafieken tonen gewichtsprogressie en persoonlijke records.

2. Overzicht van wekelijkse trainingsfrequentie en totaal aantal uitgevoerde oefeningen.


Doelen stellen:

1. Doelen instellen zoals: Ik wil 10 kilo meer liften met Bench press binnen een maand.

2. Optioneel: Automatische updates over voortgang naar het gestelde doel.


Dit is een realistische applicatie omdat het gebruikt kan worden om je sportschool progressie bij te houden. Dit is iets waar al meerdere (web)applicaties voor zijn gemaakt en ook mijn inspiratie achter dit project.
Dit gaat verder dan wat in de lessen is besproken door het gebruik van onderandere grafieken, de grote van de applicatie en de complexiteit van de applicatie (specifiek de workouts waren "interessant" om te implementeren).

###CSS:
Voor dit project is een klein beetje eigen css gebruikt, maar voor het meeste is Bootstrap gebruikt. 
Door het gebruikt van Bootstrap is de applicatie responsive. 
Er zijn voor deze applicatie vooral standaard templates gebruikt en heb daar niet veel extra vorm aan gegeven.

###Frontend architecture:
In de frontend zijn de routes te vinden onder src/router/index.js
Statemanagement is opgezet met Pinia en is terug te vinden in src/stores/auth.js

###REST API:
Filtering is geimplementeerd.
Pagination is helaas (nog) niet geimplementeerd.
Het retourneren van een error is ook ondersteeund doormiddel van de respondWithError methode in de controller.php

###Authentication:
Role Based Access Control is geimplementeerd voor de Backend is dit terug te zien in de router onder middleware (Note!: Voor dit project is in de Backend de Bramus router gebruikt :)), voor de Frontend is dit terug te zien in bijvoorbeeld de header component, waar links niet worden getoont als een gebruiker niet is ingelogd.