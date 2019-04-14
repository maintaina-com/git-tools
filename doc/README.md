# Anatomy of git-tools and components

git-tools is a modular command line tool set for horde framework developers.
It derives most of its functionality from the older horde/components application.
While components itself can be called separately and mostly deals with tasks
related to meta data, release management and quality checks, git-tools wraps
it together with functionality to compose a full git checkout and link it into a
web server consumable tree structure, similar to what the pear installer does
to pear packages. git-tools is designed to be easily extended by new modules.

While git-tools and components share some code, there are subtle differences
between git-tools level code and components level code.

## General differences

Git-Tools is designed using PHP Namespaces while components mostly is code from
pre-namespace era. git-tools was designed to be run from a composer environment,
while components is much more reliant on horde infrastructure like the
Horde_Autoloader class. This may change in the future.
The components app itself has some basic unit testing while git-tools has not.
Pear packaging infrastructure is available for components only.

## Modules

The git-tools application recognizes functionality by modules. Modules are
classes which provide both human readable and code usable meta information
on parameters needed to execute actions.

### Components-Level Modules

Components-Level modules are detected by scanning the /lib/Module directory.
The Base module is explicitly excluded.
Each found module's handle() method is supplied the Components_Configs object.
This object contains both the list of arguments and the list of parameters
of that level. Modules may process more than one level of arguments themselves.

## Git-Tools Actions

Actions are implementations of an actual capability. They take over parsed
configuration and parameter from the base environment and combine them with
any dependencies to perform a distinct action.

### Components-Level Actions

## Git-Tools Config

### Components-Level Config

## Source of Truth

In most cases, git-tools treats the local code checkout as source of truth.
With components, it is a little complicated. Older code tends to treat the 
pear xml file as authoritative while newer code treats horde.yml as the 
source for both composer and pear metadata. Some code relies on data pulled
from external parties.