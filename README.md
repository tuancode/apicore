Api Core
========

A built-in symfony project including necessary components aim to quickly build an Rest Api.

Note
------------
For MAC OS user, please install docker-sync first for addressing docker performance volumes issue
```bash
$ sudo gem install docker-sync
```

Installation
------------

First, you need to start the application environment by
```bash
$ make start
```
Then, you must build the application follow
```bash
$ make build
```
Whenever you want to stop the application run
```bash
$ make stop
```
In case of need, you can update your environment configuration in file
```bash
app/config/parameters.yml
```

Build
-----
Make sure you always re-build application after coding to refresh all new changes.
```bash
$ make build
```

Database management
-------------------
You can access application database via CLI by command
```bash
$ make db-con
```

Monitor logging
---------------
To view server log, simple run. Ctrl+C for quitting.
```bash
$ make log-sev
```
For watching application dev environment log, 'q' for quitting
```bash
$ make log-dev
```
For watching application prod environment log, 'q' for quitting
```bash
$ make log-prod
```

Open application
--------
To open application development environment
```bash
$ make open-dev
```
To open application production environment
```bash
$ make open-prod
```
