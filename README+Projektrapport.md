# webbservPROJEKT

PROJEKTRAPPORT

Vilka deluppgifter/moment från projektinstruktionerna har du gjort?

  LOGIN-delen E

  SYSTEM-delen A

  MYSQL C/A?

  PHP ?

  AJAX Ja

Beskriv i stora drag hur ditt program fungerar? Hur är koden strukturerad?

  Indexsidan är huvudsidan som inkluderar olika sidor till "main sektionen", tex sidan för rekomenderade quiz och flöde sidan.

  Andra filer som kontrolerar hela fönstret är skapaquiz, görquiz, quiz resultat, admin sida mm.

Beskriv några delar detaljerat, t ex delar som du är extra nöjd med

  gilla/ogilla systemet. 
  
  en gillning sparas i databasen med parent_id(gillade objektets id) och parent_db(gillade objektets table) på ett Objekt. Man kan gilla quiz och kommentarer,
  men systemet är skalbart att kunna fungera med andra "objekt" tex gamla quiz resultat. 

Har du några extra finesser, bra lösningar på någon del?
  I kommentarsfältet kan man kommentera på andras kommentarer

Reflektera över ditt eget arbete, ditt program, din kod, dina lösningar. Bra/dåligt/bättre/utveckling!

  Jag är nöjd med helheten, om jag hade haft med tid hade jag lagt mer tid på att ta bort buggar och implementerat byta lösenord funktionnen/tids begänsing på skapakonto delen.
  SQL hade jag velat kunna på en högre nivå. Det var många gånger jag visste att det fanns en sql lösning men som jag inte kunde genomföra, 
  det ledde till att jag fick använda mer php kod och hämta information from databasen i delar, vilket inte är bra för prestandan.
  
Skriv något om hur du upplevt projektet, lätt/svårt, kul/tråkigt, något annat? Beskriv gärna lite med egna ord.
Har du tips på hur projektet kan göras bättre?

  Jag tyckte projektet var kul!
  förslag på förbättring = mer sql

struktur, funktionalitet och innehåller användarbeskrivning

Följa konto funktionen. 
  följ ett konto genom att trycka på knappen på deras kontosida.  
  i Flödet kan man se gillningar, kommentarer och quiz resultat sorterat efter tid från konton man följer.
  
 gilla/ogilla funktionen
  användaren kan gilla quiz och kommentarer
  quizview sidan visar antalet gillningar på quizet.
  gillaknapparna innehåller antalet gillningar/ogillningar
  -förbättringsmöjlighet är att man borde kunna ta bort gilla markerinen helt, inte bara kunna byta till ogilla.
  -förbättringsmöjlighet är att antalet gillningar updateras direkt i knappen när man klickar.
  
 gamla quiz
  användarens genomförda resultat sparas och kan ses från länk på kontosidan
  Andras gamla resultat kan ses från deras kontosidor.
  -förbättringsmöjlighet, kunna sortera efter % rätt på quizet
  
 kommentera
  
 
 
