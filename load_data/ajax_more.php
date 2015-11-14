<?php
include("../include/config.php");
if(isset($_POST['lastmsg']))
{
$lastmsg=$_POST['lastmsg'];
$result=mysql_query("select * from rec_entries where id<'$lastmsg' order by id desc limit 700");
$count=mysql_num_rows($result);
while($row=mysql_fetch_array($result))
{
$msg_id=$row['id'];
$message=$row['cname'];
?>
<li>
<?php echo $message; ?>
</li>
<?php
}
?>
<div id="more<?php echo $msg_id; ?>" class="morebox">
<a href="#" id="<?php echo $msg_id; ?>" class="more">more</a>
</div>
<?php
}
?>