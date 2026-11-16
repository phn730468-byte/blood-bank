<?php require 'config.php'; need_login(); head('Dashboard');
$q=fn($sql)=>$conn->query($sql)->fetch_assoc()['c'];
$stats=['Donors'=>$q('SELECT COUNT(*) c FROM donors'),
 'Available units'=>$q('SELECT COALESCE(SUM(quantity),0) c FROM blood_units WHERE expiry_date>=CURDATE()'),
 'Pending requests'=>$q("SELECT COUNT(*) c FROM requests WHERE status='pending'"),
 'Expiring in 7 days'=>$q('SELECT COUNT(*) c FROM blood_units WHERE quantity>0 AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(),INTERVAL 7 DAY)')];
echo '<div class="grid">'; foreach($stats as $k=>$v) echo "<div class='card stat'><h1>$v</h1>$k</div>"; echo '</div><h3>Stock by blood group</h3><div class="grid">';
$r=$conn->query('SELECT blood_group g,SUM(quantity) q FROM blood_units WHERE expiry_date>=CURDATE() GROUP BY blood_group'); $have=[];
while($x=$r->fetch_assoc()) $have[$x['g']]=$x['q'];
foreach($GROUPS as $g){ $n=$have[$g]??0; echo "<div class='card stat ".($n<5?'low':'')."'><h1>$g</h1>$n units</div>"; }
echo '</div>'; foot(); ?>
