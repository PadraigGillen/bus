# bus [inactive]
A project designed to display the arrivals status of a Corvallis bus stop.

Made possible by the [Corvallis Transit System's](https://www.corvallisoregon.gov/cts)
accurate, publically accessible API.

## Design motivations
- Results should load instantly.
- Each stop should have a unique URL for easy bookmarking.
- The display should be simple and readable at a glance for easy mobile use.
- Results should update automatically at least once every 30 seconds.
- As the bus nears the stop, results should update every 15 seconds.

## Added features
- Show the arrival time for the closest bus in the favicon.
  - Useful mainly on a desktop, since the user just needs to glance at a tab in the background.

## Design Process
- PHP was chosen for server side processing to take advantage of OSU's College of Engineering's
  [student web hosting](https://web.engr.oregonstate.edu/).
- The initial code was adapted from existing PHP projects I had at the time, and was slowly
  added to when I wanted a new feature.
- As such, the code runs in one big PHP block. Since it suited my purposes, and the point
  of the project was to save me time,  I chose not to refactor it.
