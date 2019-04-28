<?php
	ob_start();
	session_start();
	$pageTitle='Home | TDL';
	include 'init.php';

	$control=isset($_GET['control']) ? $_GET['control']:'Manage';

	if($control=='Manage') {
		$stmt=$connect->prepare("SELECT list.*,users.Username FROM list INNER JOIN users ON users.UserID=list.User_ID WHERE Date=? AND Username=?  ORDER BY txtDeco DESC");
		$stmt->execute(array(date('Y-m-d'),$_SESSION['name']));
		$row=$stmt->fetchAll();
		$count=$stmt->rowCount();
?>	
		<div class="list">
			<div class="container">
			<?php
				if(!isset($_SESSION['name'])) {
					echo '<h2>You need to <a href="login.php">Login</a> to make your to do list.</h2>';
				}
				else {
			?>
					<form action="index.php?control=Add" method="POST">
						<input type="text" name="task" placeholder="eg. Homework" required="required">
				<?php 
					  $arrayDates=array(date('Y-m-d'),date('Y-n-j'),date('Y-n-d'),date('Y-m-j'),date('y-m-d'),date('y-m-j'),date('y-n-d'),date('y-n-j'));

					  if(isset($_GET['date']) && !in_array($_GET['date'],$arrayDates,true)) { ?>
						<input type="submit" value="Add" disabled="disabled" style="background:#f5f5f5;cursor:not-allowed;color:#555">
				<?php }
					  else { ?>
						<input type="submit" value="Add">
				<?php } ?>		
					</form>	
					<form action="index.php?date=" method="GET">
						<select name="date">
							<option value="0">Select a date.</option>
							<?php
								$duplicate=null;

								$statement=$connect->prepare("SELECT ID,Date FROM list WHERE User_ID=? ORDER BY Date Desc");
								$statement->execute(array($_SESSION['ID']));
								$fetchDate=$statement->fetchAll();
								
								foreach($fetchDate as $fetchDates) {
									if($fetchDates['Date']!=$duplicate) {
										echo '<option value="' . $fetchDates['Date'] . '">' . $fetchDates['Date'] . '</option>';
										$duplicate=$fetchDates['Date'];
									}
								}
							?>
						</select>
						<input type="submit" value="Go">
					</form>
				<?php   
					if(isset($_GET['date'])) {
						if($_SERVER['REQUEST_METHOD']=='GET') {
							$date=$_GET['date'];

							$setDate=$connect->prepare("SELECT list.*,users.Username FROM list INNER JOIN users ON users.UserID=list.User_ID WHERE Date=? AND Username=? ORDER BY txtDeco DESC");
							$setDate->execute(array($date,$_SESSION['name']));
							$rowDate=$setDate->fetchAll(); 
							$dateCount=$setDate->rowCount();
							if($dateCount>0) { ?>				
								<table>
									<tr>
										<th>Date</th>
										<th>Tasks</th>
										<th>Control</th>
									</tr>
						<?php 	foreach($rowDate as $rowDates) { ?>
									<tr>
										<td><?php echo $rowDates['Date'] ?></td>
										<td>
									<?php 
											echo '<span style="text-decoration:' . $rowDates['txtDeco'] . '">' . $rowDates['tasks'] . '</span>'; 
											if($rowDates['Time']!=null) { ?>
												<span class="done-time"><?php echo 'Done at ' . $rowDates['Time'] ?></span>
									<?php   } ?> 											
										</td>										
										<td>
										<?php
											if(isset($_GET['date']) && !in_array($_GET['date'],$arrayDates,true)) {
												if($rowDates['txtDeco']=='line-through') {
													echo '<i class="fa fa-reply" style="cursor:not-allowed" title="You can\'t undo old tasks."></i>';
												}
												echo '<i class="fa fa-check" style="cursor:not-allowed" title="You can\'t do old tasks."></i>';
												echo '<i class="fa fa-close" style="cursor:not-allowed" title="You can\'t delete old tasks."></i>';
											}
											else {
												if($rowDates['txtDeco']=='line-through') {
													echo '<a href="index.php?control=Undo&taskid=' . $rowDates['ID'] . '&date=' . $_GET['date'] . '"><i class="fa fa-reply"></i></a>';
												}
												echo '<a href="index.php?control=Done&taskid=' . $rowDates['ID'] . '&date=' . $_GET['date'] . '"><i class="fa fa-check"></i></a>';
												echo '<a href="index.php?control=Delete&taskid=' . $rowDates['ID'] . '&date=' . $_GET['date'] . '"><i class="fa fa-close"></i></a>';
											}
										?>
										</td>
									</tr>
						<?php   } ?>
								</table>
			<?php			}
							else {
								echo '<h2 style="margin-top:14px">Please select a date within the options.</h2>';
							}							
						}
					}
					else {
						if($count>0) { ?>
							<table>
								<tr>
									<th>Date</th>
									<th>Tasks</th>
									<th>Control</th>
								</tr>
					<?php   foreach($row as $rows) { ?>
								<tr>
									<td><?php echo $rows['Date'] ?></td>
									<td>
									<?php 
											echo '<span style="text-decoration:' . $rows['txtDeco'] . '">' . $rows['tasks'] . '</span>'; 
											if($rows['Time']!=null) { ?>
												<span class="done-time"><?php echo 'Done at ' . $rows['Time'] ?></span>
									<?php   } ?> 											
										</td>
									<td>
									<?php
										if($rows['txtDeco']=='line-through') {
											echo '<a href="index.php?control=Undo&taskid=' . $rows['ID'] . '"><i class="fa fa-reply"></i></a>';
										}
										echo '<a href="index.php?control=Done&taskid=' . $rows['ID'] . '"><i class="fa fa-check"></i></a>';
										echo '<a href="index.php?control=Delete&taskid=' . $rows['ID'] . '"><i class="fa fa-close"></i></a>';
									?>
									</td>
								</tr>
					<?php   }    ?>
							</table>
			<?php       }
						else {
							echo '<h2 style="margin-top:14px">No tasks added for today.</h2>';
						}
					}	
				}		
			?>
			</div>
		</div>
<?php
	}

	/**********************************************************************************/

	elseif($control=='Add') {
		if($_SERVER['REQUEST_METHOD']=='POST') {
			$tasks=$_POST['task'];
			insertDB('list','tasks','User_ID','Date',$tasks,$_SESSION['ID'],date('Y-m-d'),'index.php');
		}
	}

	/**********************************************************************************/

	elseif($control=='Undo') {
		$taskid=isset($_GET['taskid']) && is_numeric($_GET['taskid']) ? intval($_GET['taskid']) : 0;
		updateDB('list','txtDeco','Time','ID','none',null,$taskid);
	}

	/**********************************************************************************/

	elseif($control=='Done') {
		$taskid=isset($_GET['taskid']) && is_numeric($_GET['taskid']) ? intval($_GET['taskid']) : 0;
		updateDB('list','txtDeco','Time','ID','line-through',date('h:i:s A'),$taskid);
	}

	/**********************************************************************************/

	elseif($control=='Delete') {
		$taskid=isset($_GET['taskid']) && is_numeric($_GET['taskid']) ? intval($_GET['taskid']) : 0;
		if(isset($_GET['date'])) {
			$stmt=$connect->prepare("DELETE FROM list WHERE ID=?");
			$stmt->execute(array($taskid));
			header("Location:index.php?date=" . $_GET['date']);
			exit();
		}
		else {
			$stmt=$connect->prepare("DELETE FROM list WHERE ID=?");
			$stmt->execute(array($taskid));
			header("Location:index.php");
			exit();
		}
	}

	include 'includes/templates/footer.php';
	ob_end_flush();
?>	