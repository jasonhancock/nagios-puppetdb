<?php

$alpha = 'CC';

$colors_allowed = '#AAAAAA' . $alpha;
$colors_used    = '#0000FF' . $alpha;

$ds_name[1] = 'Memory';
$opt[1] = sprintf('-T 55 -l 0 --vertical-label "Bytes" --title "%s / PuppetDB Heap Memory Usage"', $hostname);
$def[1] = '';

$def[1] .= rrd::def('var1', $rrdfile, $DS[1], 'AVERAGE');
$def[1] .= rrd::area('var1', $colors_allowed, "Allowed\t");
$def[1] .= rrd::gprint('var1', array('LAST'), "%4.2lf %s\\t");

$def[1] .= rrd::def('var2', $rrdfile, $DS[2], 'AVERAGE');
$def[1] .= rrd::area('var2', $colors_used, "Used\t");
$def[1] .= rrd::gprint('var2', array('LAST', 'AVERAGE', 'MAX'), "%4.2lf %s\\t");
