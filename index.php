
<?php
include('./config.php');

$cat_sql = "select distinct name, id from categories order by name";
$cats = $db->query($cat_sql);

?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
	
	<label for="project">Project:</label>
	<select name="project">
		<option></option>
		<?php
			while($row = $cats->fetch_assoc()){
				echo "<option  value=".$row['id'].">".$row['name']."</option>";
			}
		?>
	</select>
	<label for="new_project">New Project:</label>
	<input type="text" name="new_project">
	<!--
		<label for="file_name" type="text">Note Title</label>
		<input type='text' name='file_name'>
	-->
	<input type="file" name="file">
	<textarea name="notes" placeholder="notes"></textarea>
	<input type="submit" >
</form>
<div id="projects">
	<?php
		$project_sql = "select * from images";
		$projects = $db->query($project_sql);
		while($row = $projects->fetch_assoc()){
			$i = 1;
		}
	?>

<?php

// 
if(
   isset($_POST) &&
  (isset($_POST['project'])||isset($_POST['new_project'])) &&
  (isset($_POST['file']) || isset($_POST['notes']))
  ){
	// inserts a new category into the categories table
	if(isset($_POST['new_project']) && !empty($_POST['new_project'])){
		
		$new_cat_sql = "insert into categories (name) values ('".$db->real_escape_string($_POST['new_project'])."')";
		$db->query($new_cat_sql);
		$cat_id = $db->insert_id;
		if($db->error){echo "1. ".$db->error."<br>";}
	}
	// uploads file
	$filename = isset($_FILES['file']['name']) ? $_FILES['file']['name']: false;
	$project  = isset($cat_id) ?  (int)$cat_id : (int)$_POST['project'] ;
	$notes  = isset($_POST['notes']) ? $db->real_escape_string($_POST['notes']) : '';
	if($filename && !file_exists($filename)){
		move_uploaded_file($_FILES['file']['tmp_name'],"./images/".$filename);
		create_thumbnail($filename);
	}
	else{
		echo"<h1>Image not uploaded</h1>";
	}
	//inserts stuff into images table
	if(!empty($filename)){
		$insert_sql = "insert into images (file,category,notes) values ('$filename',$project,'{$_POST['notes']}')";
	}
	else{
		$insert_sql = "insert into images (category,notes) values ($project,'{$_POST['notes']}')";
	}
	$db->query($insert_sql);
	if($db->error){echo "2. ". $db->error. "<br>";}
	


}

// this section gets and echos out the projects, images, and notes
$select_sql = "Select images.id as id, categories.name, file , notes from images
			   inner join categories
				 on categories.id = images.category order by category";
$all_projects = $db->query($select_sql);
echo $db->error;
$last_cat = "";
while($row = $all_projects->fetch_assoc()){
	$cat = $row['name'];
	if($cat != $last_cat){
		echo "<hr>";
		echo "<h1>$cat</h1>";
	}
	$last_cat = $cat;
	?>
<div class="single">
	<div class="img-holder">
		
		<?php if(strlen($row['file']) > 9){ ?>
			<a href="images/<?php echo $row['file'];?>"><img src="thumbs/<?php  echo $row['file'];?>"></a>
		<?php }else { ?>
			<div class="img-placeholder"></div>
		<?php } ?>
	</div>
	<div class="note-holder">
		<?php if(!empty($row['notes'])){ ?>
			<h3>Notes:</h3>
			<p>
				<?php echo $row['notes'];?>
			</p>
		<?php }else { ?>
			<div class="note-placeholder"></div>
		<?php } ?>
	</div>
	<div class="options">
		<a href="/workspace/del.php?id=<?php echo $row['id'];?>"><button id="del_button" data-id="<?php echo $row['id'];?>">Delete</button></a>
		<a href="/workspace/edit.php?id=<?php echo $row['id'];?>"><button id="edit_button" data-id="<?php echo $row['id'];?>">Edit</button></a>
	</div>
</div>
<?php	
}

?>

</div>

