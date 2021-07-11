#  Radio app
***

This is a demo version of radio app.

On a user panel is represented a media player where user can listen radio stations selected by:

 * genre
 * country
 * name

Also there is an admin section, where is available crud actions (country, genre or radio station) for an admin.

For the demo version there is a test admin user - **visitor** with pass: **111**.

###Load data
```
php bin/console app:load:countries

php bin/console app:load:genres

php bin/console app:load:users
```

**Note**: Do not forget set a new admin password in LoadUsersCommand (app:load:users). 

Sometimes the url of radio station resource could be changed to identify that and quickly fix the next command
should be run as a cron task:
```
0 1 * * * php {path_to_app} bin/console app:check:channel:resource
```

The demo version is represented on [radioApp](http://37.77.106.152 "radioApp link").
