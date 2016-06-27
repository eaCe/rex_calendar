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

Nuzung im Frontend - Im Modul folgende Zeile einbinden:

```php
require_once( rex_path::addon('rex_calendar', 'lib/fefunctions.php') );
```

Funkionen im Frontend (werde ich demnächst näher beschreiben...):

```php
getEventsByMonth($month = false, $year = false, $limit = false);
getEventsByYear($year = false, $limit = false);
getEventsByDay($day, $month, $year);
getNextEvents($day = false, $month = false, $year = false, $limit = false);
getImageById($id, $mediaPath = false, $setTitle = false);
getEvenByCategory($category, $day = false, $month = false, $year = false, $limit = false);
```