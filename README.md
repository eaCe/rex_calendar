# :warning: Dieses Addon wird nicht mehr weiterentwickelt! Ein umfangreiches Kalender-Addon für R5 findet ihr hier: [sked](https://github.com/FriendsOfREDAXO/sked)

---

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
- [ ] iCal Export
- [ ] iCal Import
- [ ] Jahresübersicht
- [ ] Listenansicht

![Kalender](https://raw.githubusercontent.com/eaCe/rex_calendar/assets/calendar.jpg)
![Eintrag](https://raw.githubusercontent.com/eaCe/rex_calendar/assets/edit.jpg)

Nuzung im Frontend - Im Modul folgende Zeile einbinden:

```php
require_once( rex_path::addon('rex_calendar', 'lib/fefunctions.php') );
```

## Funktionen im Frontend:

```php
getEventById($id); 
//gibt ein array zurück
//ID wird benötigt
```

```php
getEventsByMonth($month = false, $year = false, $limit = false); 
//gibt ein array zurück
//sofern keine Parameter übergeben werden, wird der aktuelle Monat benutzt, alle vorhandenen Einträge werden ausgegeben
```

```php
getEventsByYear($year = false, $limit = false); 
//gibt ein array zurück
//sofern keine Parameter übergeben werden, wird das aktuelle Jahr benutzt, alle vorhandenen Einträge werden ausgegeben
```

```php
getEventsByDay($day, $month, $year); 
//gibt ein array zurück
//Tag, Monat und Jahr werden benötigt
```
```php
getNextEvents($day = false, $month = false, $year = false, $limit = false); 
//gibt ein array zurück
//sofern keine Parameter übergeben werden, wird das aktuelle Datum benutzt, alle vorhandenen Einträge werden ausgegeben
```

```php
getImageById($id, $mediaPath = false, $setTitle = false); 
//gibt ein img tag zurück
//ID wird benötigt
//wenn nicht angegeben ist $mediaPath = 'media/';
//wenn $setTitle wird ein title-Attribut mit asugegeben;
```

```php
getEvenByCategory($category, $day = false, $month = false, $year = false, $limit = false); 
//gibt ein array zurück
//Kategorie als string wird benötigt (wie in den Einstellungen eingegeben)
//wenn keine weiteren Parameter übergeben werden beginnt die Ausgabe ab dem aktuellem Datum
```


### Beispiel Output im Modul:

```php
<?php
require_once( rex_path::addon('rex_calendar', 'lib/fefunctions.php') );

$myEvent = getEventById(8);

var_dump($myEvent);
```

### Das Ergebnis wäre dann:
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

## Lizenz

rex_calendar ist unter der [MIT Lizenz](LICENSE.md) lizensiert.
