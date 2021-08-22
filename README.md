# Pyp
[![Build Status](https://app.travis-ci.com/dayallnash/pyp.svg?branch=master)](https://app.travis-ci.com/dayallnash/pyp)

The Pyp social network is coming...

## Installing locally

### Initial setup

Clone this repository to your local filesystem, to a directory named `pyp`.

Setup Docker on your machine. See https://www.docker.com/get-started for more info on how to get started with Docker.

First, we need to install Pyp's dependencies on your machine using Composer. Using your favourite terminal application, from the `pyp` directory, run:
```
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install --no-interaction
```

You should see a list of dependencies being installed on your machine.

Setup Vagrant and Virtualbox. See https://www.vagrantup.com/docs for more info on how to get started with Vagrant.

Edit your local hosts file to point the `pyp.local` address to `192.168.10.10`. On the Windows hosts file, located at C:\Windows\System32\drivers\etc\hosts, your will look end up looking something like:

```
# Copyright (c) 1993-2009 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
#      102.54.94.97     rhino.acme.com          # source server
#       38.25.63.10     x.acme.com              # x client host

# localhost name resolution is handled within DNS itself.
#	127.0.0.1       localhost
#	::1             localhost

192.168.10.10  pyp.local
```

### Using Vagrant for daily development

Using your favourite terminal application, run `vagrant up` from the `pyp` directory. Wait a few minutes for Virtualbox to load your box.

Next up, run `vagrant ssh` to open an interactive shell to your local Pyp installation's 'server'. From here, you can run any Symfony console command and interact with the MySQL database.

Navigate to the `pyp` directory on your Vagrant box and run `php bin/console doctrine:migrations:migrate` to get Doctrine to build your database schema for you from the migrations files.

To view your local Pyp installation from your browser, navigate to https://pyp.local. If everything has worked properly, then you should see the Pyp homepage and should be able to register a new account.

When you are done with development, make sure to run `vagrant suspend` to save the state of the box. When you come back to it, you can simply run `vagrant up` again to pick up where you left off!