nagios-puppetdb
===============

A collection of Nagios scripts/plugins for monitoring PuppetDB.


LICENSE: MIT
------------
Copyright (c) 2012 Jason Hancock <jsnbyh@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

PLUGINS:
--------

**check_puppetdb_dups:**

This check reports the percent of catalogs and resources that are duplicated.
It doesn't accept thresholds as it's mainly informational. The information
will probably not be useful in the short term, but will be interesting to
trend over time.

![check_puppetdb_dups](https://github.com/jasonhancock/nagios-puppetdb/raw/master/example-images/check_puppetdb_dups.png)

**check_puppetdb_memory:**

This check reports on Java's heap memory usage. It accepts warning and critical
thresholds expressed as percents of the maximum allowed usage.

![check_puppetdb_memory](https://github.com/jasonhancock/nagios-puppetdb/raw/master/example-images/check_puppetdb_memory.png)

**check_puppetdb_populations:**

This check reports on resource and node populations. It generates a total of
three graphs: average resources per node, node count, and resource count (only
showing the average resources per node graph):

![check_puppetdb_populations](https://github.com/jasonhancock/nagios-puppetdb/raw/master/example-images/check_puppetdb_populations.png)

**check_puppetdb_processed:**

This check reports on how many commands have been processed by PuppetDB.

![check_puppetdb_processed](https://github.com/jasonhancock/nagios-puppetdb/raw/master/example-images/check_puppetdb_processed.png)

**check_puppetdb_queue:**

This plugin reports on PuppetDB's internal command queue depth. It's currently 
using the default pnp4nagios template as it's a simple parameter to plot. The
example image below shows what happens when you stop Postgres for a while... the
commands back up in the queue, then get processed as soon as Postgres is
started. For the size of my site, I expect this to hover around 0. Your site
may be different, so be prepared to tune the thresholds.

![check_puppetdb_queue](https://github.com/jasonhancock/nagios-puppetdb/raw/master/example-images/check_puppetdb_queue.png)

INSTALLATION:
-------------

Copy the plugins out of the plugins directory and put them into Nagios' plugins
directory on the Nagios server (this is usually /usr/lib64/nagios/plugins on 
a 64-bit RHEL/CentOS box). 

Copy the pnp4nagios templates out of the pnp4nagios/templates directory and put
them into pnp4nagios' templates directory (On EL6 using the pnp4nagios package
from EPEL, this directory is /usr/share/nagios/html/pnp4nagios/templates).

Copy the pnp4nagios check commands configs out of the pnp4nagios/check\_commands
directory and put them in pnp4nagios' check\_commands directory. Using the same
package from EPEL as above, this is /etc/pnp4nagios/check\_commands. Do this
BEFORE configuring the service checks in Nagios otherwise the RRD's will get 
created with the wrong data types (To fix this, just delete the .rrd files and
start over).

NAGIOS CONFIGURATION:
---------------------

Decide if you are going to access PuppetDB via the default plain text http port
(8080) or if you are going to front that with apache/nginx and terminate ssl
with that server. The plugins were not designed to access PuppetDB's ssl on port
8081. The command definitions below assume that you are accessing PuppetDB via a
webserver running with ssl enabled on port 443 and then proxy-passing that
traffic to PuppetDB on port 8080 on localhost.

If you are proxy-passing the traffic and you have authentication turned on, you
will want to allow your Nagios host access without authentication (or modify
the plugins to accept/use a username and password). For Apache, this can be
accomplished with an 'Allow' directive combined with 'Satisfy any' where you are
configuring your authentication.

```
define command{
    command_name check_puppetdb_dups
    command_line $USER1$/check_puppetdb_dups -H $HOSTADDRESS$ -p 443 -s
}

define command{
    command_name check_puppetdb_memory
    command_line $USER1$/check_puppetdb_memory -H $HOSTADDRESS$ -p 443 -s -w 80 -c 90
}

define command{
    command_name check_puppetdb_populations
    command_line $USER1$/check_puppetdb_populations -H $HOSTADDRESS$ -p 443 -s
}

define command{
    command_name check_puppetdb_processed
    command_line $USER1$/check_puppetdb_processed -H $HOSTADDRESS$ -p 443 -s
}

define command{
    command_name check_puppetdb_queue
    command_line $USER1$/check_puppetdb_queue -H $HOSTADDRESS$ -p 443 -s -w 100 -c 200
}

define service {
    check_command                  check_puppetdb_populations
    host_name                      puppetdb.example.com
    service_description            PuppetDB Populations
    use                            generic-service-graphed
}

define service {
    check_command                  check_puppetdb_dups
    host_name                      puppetdb.example.com
    service_description            PuppetDB Duplication
    use                            generic-service-graphed
}

define service {
    check_command                  check_puppetdb_memory
    host_name                      puppetdb.example.com
    service_description            PuppetDB Java Heap
    use                            generic-service-graphed
}

define service {
    check_command                  check_puppetdb_processed
    host_name                      puppetdb.example.com
    service_description            PuppetDB Command Processing 
    use                            generic-service-graphed
}

define service {
    check_command                  check_puppetdb_queue
    host_name                      puppetdb.example.com
    service_description            PuppetDB Command Queue Depth 
    use                            generic-service-graphed
}
```
