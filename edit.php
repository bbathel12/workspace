<?php
include('./config.php');
$id = !empty($_POST)? $_POST['id']: $_GET['id'];
$select_sql = "select categories.id as cid ,categories.name, images.file, notes from images
				inner join categories on images.category = categories.id
				where images.id = ".$id;
$result = $db->query($select_sql);
echo $db->error;
$image = $result->fetch_assoc();

$cat_sql = "select distinct name, id from categories order by name";
$cats = $db->query($cat_sql);

?>
<a href="//<?php echo $_SERVER['HTTP_HOST'];?>/workspace?id=<?php echo $id ?>"><h1>Home</h1></a>
<div>
	<form id="edit-form" action="//<?php echo $_SERVER['HTTP_HOST'];?><?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $id ?>" method="post">
		<input type="hidden" name=id value="<?php echo $id; ?>">
		<?php if($image['file']!='false'){?>
			<img class="edit-img" src="/workspace/thumbs/<?php echo $image['file'];?>">
		<?php } ?>
		<select name="project">
			<?php
				while($row = $cats->fetch_assoc()){
					$selected = ($row['id'] === $image['cid'])? "selected=selected": "";
					echo "<option $selected  value=".$row['id'].">".$row['name']."</option>";
				}
			?>
		</select>
		<textarea name="notes"><?php echo $image['notes'];?></textarea>
		<input type="submit">
	</form>
</div>
<?php
if(!empty($_POST)){
	$update_sql= "update images set notes='".$db->real_escape_string($_POST['notes'])."', category='".(int)$_POST['project']."' where id=".$_POST['id'];
	$db->query($update_sql);
	echo $db->error;
	echo "<h1>".$db->error.(int)$_POST['project']."</h1>";
$location =$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?id=". $id ;

header("location://$location");
}
