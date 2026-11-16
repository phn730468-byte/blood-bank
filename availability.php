<?php require 'config.php'; need_login(); head('Blood Availability');
$r=$conn->query('SELECT blood_group g,SUM(quantity) q FROM blood_units WHERE expiry_date>=CURDATE() GROUP BY blood_group'); $have=[];
while($x=$r->fetch_assoc()) $have[$x['g']]=$x['q'];
echo '<div class="grid">'; foreach($GROUPS as $g){ $n=$have[$g]??0; echo "<div class='card stat ".($n<5?'low':'')."'><h1>$g</h1>$n units<br><small>".($n>0?'Available':'Out of stock')."</small></div>"; }
echo '</div>'; foot(); ?>
