<?php

$color = '#0000FFCC'; 

$opt[1] = "-T 55 -l 0 --vertical-label \"Commands/s\" --title \"$hostname / PuppetDB Commands Per Second\"";
$def[1]  = rrd::def("var0", $rrdfile, $DS[1], 'AVERAGE');
$def[1] .= rrd::area("var0", $color, 'Messages/s');
$def[1] .= rrd::gprint("var0", array('LAST','MAX','AVERAGE'), "%4.2lf %s\\t");
