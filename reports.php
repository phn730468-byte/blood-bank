<?php require 'config.php'; need_login();
$R=['donors'=>['Donor details','SELECT id,name,age,gender,contact,blood_group,last_donation FROM donors',['ID','Name','Age','Gender','Contact','Group','Last donation']],
'inventory'=>['Blood group-wise inventory','SELECT blood_group,SUM(quantity) FROM blood_units WHERE expiry_date>=CURDATE() GROUP BY blood_group',['Group','Available units']],
'units'=>['Available blood units','SELECT id,blood_group,quantity,collection_date,expiry_date,storage FROM blood_units WHERE quantity>0 AND expiry_date>=CURDATE()',['ID','Group','Units','Collected','Expiry','Storage']],
'requests'=>['Blood request details','SELECT id,requester,contact,blood_group,quantity,request_date,status FROM requests',['ID','Requester','Contact','Group','Units','Date','Status']],
'decided'=>['Approved & rejected requests',"SELECT id,requester,blood_group,quantity,status FROM requests WHERE status IN('approved','rejected','issued')",['ID','Requester','Group','Units','Status']],
'issued'=>['Blood issue records','SELECT id,request_id,blood_group,quantity,issue_date FROM issues',['ID','Request','Group','Units','Date']],
'expiry'=>['Units approaching expiry (7 days)','SELECT id,blood_group,quantity,expiry_date FROM blood_units WHERE quantity>0 AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(),INTERVAL 7 DAY)',['ID','Group','Units','Expiry']]];
$k=$_GET['r']??'inventory'; if(!isset($R[$k])) $k='inventory';
head('Reports'); echo '<p class="noprint">'; foreach($R as $key=>$v) echo "<a class='badge' href='?r=$key'>{$v[0]}</a> "; echo '<button onclick="window.print()">Print</button></p><h3>'.$R[$k][0].'</h3>';
table($conn->query($R[$k][1]),$R[$k][2]); foot(); ?>
