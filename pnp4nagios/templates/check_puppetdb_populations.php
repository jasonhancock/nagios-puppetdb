<?php

$color = '#0000FFCC';

$ds_name[1] = 'resources / nodes';
$opt[1]  = sprintf('-T 55 -l 0 --vertical-label "Resources/Node" --title "%s / PuppetDB Average Resources Per Node"', $hostname);
$def[1]  = rrd::def('var1', $rrdfile, $DS[1], 'AVERAGE');
$def[1] .= rrd::def('var2', $rrdfile, $DS[2], 'AVERAGE');
$def[1] .= rrd::cdef('math1', 'var2,var1,/');
$def[1] .= rrd::area('math1', $color, 'Resources Per Node');
$def[1] .= rrd::gprint('math1', array('LAST'), "%4.0lf %s");

$ds_name[2] = 'nodes';
$opt[2] = sprintf('-T 55 -l 0 --vertical-label "# Nodes" --title "%s / PuppetDB Node Count"', $hostname);
$def[2]  = rrd::def('var1', $rrdfile, $DS[1], 'AVERAGE');
$def[2] .= rrd::area('var1', $color, 'Nodes');
$def[2] .= rrd::gprint('var1', array('LAST'), "%4.0lf %s");

$ds_name[3] = 'resources';
$opt[3]  = sprintf('-T 55 -l 0 --vertical-label "# Resources" --title "%s / PuppetDB Resource Count"', $hostname);
$def[3]  = rrd::def('var1', $rrdfile, $DS[2], 'AVERAGE');
$def[3] .= rrd::area('var1', $color, 'Resources');
$def[3] .= rrd::gprint('var1', array('LAST'), "%4.2lf %s");

