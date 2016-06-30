Redaxo 5 Addon - Kalender
=================================

Dieses Addon stellt Kalender im Backend zur verfügung.

Momentan enthalten:

- [x] Veranstaltungen an einem Tag hinzufügen
- [x] Start- und Endzeit 
- [x] Titel und Beschreibung
- [x] Bilder
- [x] Kategorien bestimmen
- [x] Nutzung von Markitup
- [x] Ausgabe im Frontend
- [X] Editieren der Veranstaltungen
- [ ] Mehrtägige veranstaltungen

![Kalender](https://raw.githubusercontent.com/eaCe/rex_calendar/assets/calendar.jpg)
![Eintrag](https://raw.githubusercontent.com/eaCe/rex_calendar/assets/edit.jpg)

Nuzung im Frontend - Im Modul folgende Zeile einbinden:

```php
require_once( rex_path::addon('rex_calendar', 'lib/fefunctions.php') );
```

Funkionen im Frontend:

```php
getEventById($id); //gibt ein array zurück
getEventsByMonth($month = false, $year = false, $limit = false); //gibt ein array zurück
getEventsByYear($year = false, $limit = false); //gibt ein array zurück
getEventsByDay($day, $month, $year); //gibt ein array zurück
getNextEvents($day = false, $month = false, $year = false, $limit = false); //gibt ein array zurück
getImageById($id, $mediaPath = false, $setTitle = false); //gibt img tag zurück
getEvenByCategory($category, $day = false, $month = false, $year = false, $limit = false); //gibt ein array zurück
```


Beispiel Output im Modul:

```php
<?php
require_once( rex_path::addon('rex_calendar', 'lib/fefunctions.php') );

$myEvent = getEventById(8);

var_dump($myEvent);
```

Das Ergebnis wäre dann:
```php
array (size=13)
  'id' => string '8' (length=1)
  'startdate' => string '2016-06-15' (length=10)
  'enddate' => string '0000-00-00' (length=10)
  'day' => string '15' (length=2)
  'month' => string '6' (length=1)
  'year' => string '2016' (length=4)
  'title' => string 'Mein Termin 123' (length=15)
  'starttime' => string '11:20' (length=5)
  'endtime' => string '14:20' (length=5)
  'description' => string 'Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte. Abgeschieden wohnen sie in Buchstabhausen an der Küste des Semantik, eines großen Sprachozeans. Ein kleines Bächlein namens Duden fließt durch ihren Ort und versorgt sie mit den nötigen Regelialien. Es ist ein paradiesmatisches Land, in dem einem gebratene Satzteile in den Mund fliegen. Nicht einmal von der allmächtigen Interpunktion werden die Blindtexte beherrscht – ein geradezu unorthographisches '... (length=641)
  'image' => string 'panorama-kuba2.jpg' (length=18)
  'category' => string 'cocktail' (length=8)
  'link' => string '/rex5/' (length=10)
```