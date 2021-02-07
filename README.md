# Charts_CSV_Visualizer
Visualisieren von Daten zum Stromverbrauch und der Aussentemperatur (Heizung).

Dabei werden CSV-Dateien eingelesen und mittels ChartJs https://www.chartjs.org visualisiert.
Die CSV-Dateien werden bei mir auf einem Raspberry Pi Mini eingesammelt 
  * S0-Schnittstelle für Stromverbrauch gemäß https://blog.webernetz.net/stromzahler-mit-s0-schnittstelle-vom-raspberry-pi-auswerten/
  * 1-Wire Temperatursensor DS1820 gemäß http://www.netzmafia.de/skripten/hardware/RasPi/Projekt-Onewire/index.html

## Initialisierung: 
  * Ausführen von scripts/create_sample_data.py
  * Danach Upload des public_html-Verzeichnisses in Verzeichnis (z.B. charts_visualizer) auf Webserver

## Benennung der CSV-Dateien
 zaehler_kwh_&lt;YYYY&gt;&lt;MM&gt;.csv, z.B. zaehler_kwh_202101.csv

## Aussehen der CSV-Dateien
Datum; Uhrzeit; Zaehler; kWh; Temperatur

2020-1-1;0:0:57;0;0.000000;4.312000

  * Datum in der Form YYYY-MM-DD oder YYYY-M-D
  * Uhrzeit in der Form HH:MM:SS oder H:M:S
  * Zähler zählt die Ticks der S0-Schnittstelle - kann 0 bleiben
  * kWh - Kilowattstunden als Dezimalzahl (bei mir i.d.R. 0.10000)
  * Tepmperatur als Dezimalzahl 
