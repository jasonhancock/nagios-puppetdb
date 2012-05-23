<?php

$alpha = 'CC';

$colors = array(
    '#00FF00' . $alpha,
    '#0000FF' . $alpha,
);

$ds_name[1] = 'Duplication';
$opt[1] = "-T 55 --vertical-label \"Percent\" --title \"$hostname / PuppetDB Duplication\"";
$def[1] = '';

foreach ($DS as $i) {
    $def[1] .= rrd::def("var$i", $rrdfile, $DS[$i], 'AVERAGE');
    $def[1] .= rrd::line2("var$i", $colors[$i - 1], rrd::cut(ucfirst($NAME[$i]), 15));
    $def[1] .= rrd::gprint("var$i", array('LAST', 'AVERAGE', 'MAX'), "%4.2lf %s\\t");
}
