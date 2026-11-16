<?php
session_start();
$conn = new mysqli('localhost','root','','bloodbank');
if($conn->connect_error) die('Database connection failed');
$conn->set_charset('utf8mb4');
$GROUPS=['A+','A-','B+','B-','AB+','AB-','O+','O-'];
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES);}
function need_login(){ if(empty($_SESSION['admin'])){header('Location: login.php');exit;} }
function head($t){ echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($t).'</title><link rel="stylesheet" href="style.css"><script src="script.js" defer></script></head><body>';
 if(!empty($_SESSION['admin'])) echo '<nav><b>&#129656; Blood Bank</b><a href="dashboard.php">Dashboard</a><a href="donors.php">Donors</a><a href="inventory.php">Inventory</a><a href="requests.php">Requests</a><a href="issue.php">Issue</a><a href="availability.php">Availability</a><a href="reports.php">Reports</a><a href="logout.php">Logout</a></nav>';
 echo '<div class="wrap"><h2>'.e($t).'</h2>'; }
function foot(){ echo '</div></body></html>'; }
function group_select($sel=''){ global $GROUPS; $o='<select name="blood_group" required><option value="">Blood group</option>'; foreach($GROUPS as $g) $o.='<option'.($g==$sel?' selected':'').'>'.$g.'</option>'; return $o.'</select>'; }
function table($res,$cols){ echo '<div class="scroll"><table><tr>'; foreach($cols as $c) echo '<th>'.e($c).'</th>'; echo '</tr>'; while($r=$res->fetch_assoc()){ echo '<tr>'; foreach($r as $v) echo '<td>'.e($v).'</td>'; echo '</tr>'; } echo '</table></div>'; }
