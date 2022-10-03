# PlanetariumWeb
 Website development for the Planetarium - thin client (course project).
## Website functionality
- Registration of a new user.
- User authorization and use of cookies.
- The ability to delete and restore an account.
- Output of information about the events of the planetarium.
- Displaying the schedule of the event selected by the user.
- Displaying available tickets for a specific event in the schedule.
- Search for events by price, date and time.
- The ability to add and cancel the order of tickets.
- Displaying information about previously ordered tickets.
## Building and running the project
1. Install the WampServer 3 web server build.
2. Run phpMyAdmin.
3. Import from the `planetarium.sql` file containing the database.
4. Move all files to ...\wamp\www.
5. Open browser and navigate to `localhost` or `/127.0.0.1`.
## Using the PlanetariumWeb
See the ![Use Case Diagram](https://github.com/EvgeniaSap/PlanetariumWeb/issues/1#issue-1395193106) for details on the available features.

Brief explanation of the diagram:

There are two actors in PlanetariumWeb - a site "Guest" and a "Customer". "Guest" can view all materials of the site, including the repertoire of events, prices, schedule. It can also sort events and run a search by criteria.

The "Guest" can go through the registration procedure, after which he acquires the status of "Customer" and can be authenticated. "Customer" - a user who has the ability to order tickets and access to a personal account.
