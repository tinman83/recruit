<?php session_start(); ob_start();

	if(!isset($_SESSION['Level_access']) || !isset($_SESSION['user_name']) || !($_SESSION['Level_access']==1)){

		$_SESSION = array();

		if($_COOKIE[session_name()]){

			setcookie(session_name(),'',time()-86400,'/');

		}

		session_destroy();

		header("Location: logon_F.php");

		exit;

	}

	
	session_write_close();
	
	if(!$_POST){

		header("Location: jobSearch_F.php");

		exit;

	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel='stylesheet' href='css/glyphicons-only-bootstrap/css/bootstrap.min.css' />
	<link href="css/styles.css" rel="stylesheet" type="text/css">

    	<script language="javascript" type="text/javascript">

			function setCombos() {

				//Get the value of the Category Combo

				var clientCombo = jobs.elements["clientTemp"].value;

				document.jobs.client.value = clientCombo;				

				var catCombo = jobs.elements["catCodeTemp"].value;

				document.jobs.category.value = catCombo;	

				var statusCombo = jobs.elements["tempStatus"].value;

				document.jobs.status.value = statusCombo;						

				var consultantCombo = jobs.elements["tempConsultant"].value;

				document.jobs.consultant.value = consultantCombo;

				var hotindCombo = jobs.elements["temphotind"].value;

				document.jobs.hotind.value = hotindCombo;

				var careerlevelCombo = jobs.elements["careerlevelTemp"].value;

				document.jobs.careerlevel.value = careerlevelCombo;

				var locationCombo = jobs.elements["locationTemp"].value;

				document.jobs.location.value = locationCombo;

				var jobTypeCombo = jobs.elements["JTCodeTemp"].value;

				document.jobs.jobtype.value = jobTypeCombo;	

			}

        </script>	

	<title>Update a job</title>

</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="setCombos();">

	<center>

	<?php 

		@include("adminHeader.php");

		$jobID = trim(@$_POST["edit"]);

		@include('./new_includes_folder/connections.php');

		$can2jobQ = "select * from job2can where idJob = ?";

		$can2jobR = @$connection->prepare($can2jobQ);

		@$can2jobR->execute([$jobID]);

		$error_status = @$can2jobR->errorInfo();

		if(isset($error_status[2])){

			$connection = null;

			unset($connection);

			exit;

		}

	?>



<center>
	<ul class="tab" style="margin-left:130px;margin-right:130px;">
	  <li><a id="defaultOpen" href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'jobdetail')">Job</a></li>
	   <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'allocatedcandidates')">Allocated Candidates</a></li>
	  <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'jobapplicants')">Job Applicants</a></li>
	</ul>
</center>
<!-- Open job details tab -->
<div id="jobdetail" class="tabcontent">
    <form action="jobUpdate.php" method="post" name="jobs">

		<?php

			$query = "SELECT * FROM `job` WHERE ID = ?";

			$result = @$connection->prepare($query);	

			@$result->execute([$jobID]);

			$error_status = @$result->errorInfo();

			if(isset($error_status[2])){

				$connection = null;

				unset($connection);

				exit;

			}
            
			$row = @$result->fetch();

		?>

		<p class="text1"><h3>Update Job</p>

		<table width="950" align="center" class="" cellpadding="3">

		<tr>

			<td>Category</td>

			<td>

				<?php

					$query = "SELECT * FROM `category`";

					$catresult = @$connection->query($query);

					$error_status = @$connection->errorInfo();

					if(isset($error_status[2])){

						$connection = null;

						unset($connection);

						exit;

					}

					

					echo "<select name='category' id='category' >";

					while($rowCat = @$catresult->fetch())

					  {

					  echo "<option id='".htmlentities($rowCat["id"])."' value='".htmlentities($rowCat["id"])."'>".htmlentities($rowCat["name"])."</option>";

					  }			

					echo "</select>";

				?><input type="hidden" name="catCodeTemp" id="catCodeTemp" value="<?php echo $row["category"]; ?>" />

				<input type="hidden" name="jobID" value="<?php echo $_POST["edit"];  ?>" />

			</td>

		</tr>	

		<tr><td>Client Name</td>

			<td>

				<?php

					$query = "SELECT * FROM `client`";

					$result = @$connection->query($query);

					$error_status = @$connection->errorInfo();

					if(isset($error_status[2])){

						$connection = null;

						unset($connection);

						exit;

					}

					echo "<select name='client' id='client' ><option></option>";

					while($rowClient = @$result->fetch())

					  {

					  echo "<option id='".htmlentities($rowClient["companyName"])."' value='".htmlentities($rowClient["companyName"])."'>".htmlentities($rowClient["companyName"])."</option>";

					  }			

					echo "</select>";

				?><input type="hidden" name="clientTemp" id="clientTemp" value="<?php echo urldecode($row["client"]); ?>" />

			</td>

		</tr>

		<tr>

			<td>Company Type</td>

			<td><input type="text" name="companyType" size="100" value="<?php echo htmlentities(urldecode($row["type"])); ?>"/> I.e FMCG / Retail / Hardware</td>

		</tr> 

		<tr>

			<td>Title</td>

			<td><input type="text" name="title" size="50" value="<?php echo htmlentities(urldecode($row["title"])); ?>"/></td>

		</tr>

		<tr>

			<td>Type of Job</td>

			<td>

				<?php

					$queryJT = "SELECT * FROM `lk_jobtype`";

					$JTresult = @$connection->query($queryJT);

					$error_status = @$connection->errorInfo();

					if(isset($error_status[2])){

						$connection = null;

						unset($connection);

						exit;

					}

					echo "<select name='jobtype' id='jobtype' >";

					while($rowJT = @$JTresult->fetch())

					  {

					  echo "<option id='".htmlentities($rowJT["name"])."' value='".htmlentities($rowJT['name'])."'>".htmlentities($rowJT["name"])."</option>";

					  }			

					echo "</select>";

				?><input type="hidden" name="JTCodeTemp" id="JTCodeTemp" value="<?php echo $row["empType"]; ?>" />

			</td>

		</tr>    

    

		<tr>

			<td>Location</td>

			<td>

				<?php

					$query = "SELECT * FROM `lk_location`";

					$result = @$connection->query($query);

					$error_status = @$connection->errorInfo();

					if(isset($error_status[2])){

						$connection = null;

						unset($connection);

						exit;

					}

					echo "<select name='location' id='location' ><option></option>";

					while($rowLoc = @$result->fetch())

					  {

					  echo "<option id='".htmlentities($rowLoc["name"])."' value='".htmlentities($rowLoc["name"])."'>".htmlentities($rowLoc["name"])."</option>";

					  }			

					echo "</select>";

				?><input type="text" name="locationTemp" id="locationTemp" value="<?php echo htmlentities($row["location"]); ?>" />

			</td>

		<tr>

		<tr>
			<td>Location Desc.</td>
			<td><input type="text" name="locationdescription" size="50" value="<?php echo htmlentities(urldecode($row["locationdescription"])); ?>"/></td>
		</tr>

	    <tr>

			<td>Country</td>

			<td>

				<?php

					$query = "SELECT * FROM `countries`";

					$result = @$connection->query($query);

					$error_status = @$connection->errorInfo();

					if(isset($error_status[2])){

						$connection = null;

						unset($connection);

						exit;

					}

					echo "<select name='country' id='country' ><option></option>";

					while($rowLoc = @$result->fetch())

					  {

					  	$selected = ($rowLoc["country_id"] == $row["countryId"]) ? "selected" : "";

					    echo "<option ".$selected." id='country".htmlentities($rowLoc["country_id"], ENT_QUOTES, "UTF-8")."' value='".htmlentities($rowLoc["country_id"], ENT_QUOTES, "UTF-8")."'>".htmlentities($rowLoc["country_name"], ENT_QUOTES, "UTF-8")."</option>";

					  }			

					echo "</select>";

				?>
			</td>

		<tr>

		<td>Salary</td>

		<td><input type="text" name="salary" size="50" value="<?php echo htmlentities(urldecode($row["salary"])); ?>"/></td>

	</tr>

<tr>

	<td>Start Date</td>

    <td><input type="text" name="startDate" size="50" value="<?php echo htmlentities(urldecode($row["startDate"])); ?>"/></td>

</tr>

    <tr valign="top">

	<td>Summary</td>

    <td><textarea name="summary" id="summary" cols="100" rows="6"><?php echo htmlentities(urldecode($row["summary"])); ?></textarea></td>

</tr>

<tr valign="top">

	<td>Description</td>

    <td><textarea name="desc" id="desc" cols="100" rows="30"><?php echo htmlentities(urldecode($row["description"])); ?></textarea></td>

</tr>

<tr valign="top">

	<td>Key Skills</td>

    <td><textarea name="skill" id="skill" cols="100" rows="10"><?php echo htmlentities(urldecode($row["keySkills"])); ?></textarea></td>

</tr>

<tr valign="top">

	<td>Key Qualifications</td>

    <td><textarea name="qual" id="qual" cols="100" rows="10"><?php echo htmlentities(urldecode($row["qual"])); ?></textarea></td>

</tr>

<tr>

	<td>Years Experience</td>

    <td><input type="text" name="explevel" size="10" value="<?php echo htmlentities(urldecode($row["expLevel"])); ?>"/>Please enter a number!!!</td>

</tr>  

<!--<tr><td>Experience Requirements</td>

    <td>    <textarea name="expDetail" id="expDetail" cols="100" rows="7"><?php echo htmlentities(urldecode($row["expDetail"])); ?></textarea></td>

</tr>-->

    <tr>
     <td>Question 1</td>
     <td><input type="text" name="qxn1" size="50"  value="<?php echo htmlentities(urldecode($row["q1"])); ?>"/>
        <select name="ans1">
        	<option value="1" <?php if ($row["a1"]==1) {echo "selected";}else{echo "";}?>>Yes</option>
        	<option value="0" <?php if ($row["a1"]==0) {echo "selected";}else{echo "";}?>>No</option>
        </select>
     </td>
     </tr>
     <tr>
     <td>Question 2</td>
     <td><input type="text" name="qxn2" size="50" value="<?php echo htmlentities(urldecode($row["q2"])); ?>"/>
        <select name="ans2">
        	<option value="1" <?php if ($row["a2"]==1) {echo "selected";}else{echo "";}?>>Yes</option>
        	<option value="0" <?php if ($row["a2"]==0) {echo "selected";}else{echo "";}?>>No</option>
        </select>
      </td>
     </tr>
     <tr>
     <td>Question 3</td>
     <td><input type="text" name="qxn3" size="50" value="<?php echo htmlentities(urldecode($row["q3"])); ?>"/>
     	<select name="ans3">
     	<option value="1" <?php if ($row["a3"]==1) {echo " selected";}else{echo "";}?>>Yes</option>
     	<option value="0" <?php if ($row["a3"]==0) {echo " selected";}else{echo "";}?>>No</option>
     	</select>
      </td>
     </tr>

    <tr>

	<td>Tags</td>

    <td><input type="text" name="tag1" size="50" value="<?php echo htmlentities(urldecode($row["tag1"])); ?>"/>

    <input type="text" name="tag2" size="50" value="<?php echo htmlentities(urldecode($row["tag2"])); ?>"/>

    <input type="text" name="tag3" size="50" value="<?php echo htmlentities(urldecode($row["tag3"])); ?>"/>

    <input type="text" name="tag4" size="50" value="<?php echo htmlentities(urldecode($row["tag4"])); ?>"/>

    <input type="text" name="tag5" size="50" value="<?php echo htmlentities(urldecode($row["tag5"])); ?>"/>

    </td>

</tr> 

<tr><td>Degree Type</td>

    <td><input type="text" name="degree" size="50" value="<?php echo htmlentities(urldecode($row["degree"])); ?>"/></td>

</tr>    

<tr><td>Gender</td>

    <td><select name="gender" id="gender">

            <option id="Not Specified">Not Specified</option>

            <option id="Male">Male</option>

            <option id="Female">Female</option>

        </select></td>

</tr>   

<tr><td>Job Level</td>

    <td>

    	<?php

			$query = "SELECT * FROM `lk_careerlevel`";

			$result = @$connection->query($query);

			$error_status = @$connection->errorInfo();

			if(isset($error_status[2])){

				$connection = null;

				unset($connection);

				exit;

			}

			echo "<select name='careerlevel' id='careerlevel' ><option></option>";

			while($rowClient = @$result->fetch())

			  {

			  echo "<option id='".htmlentities($rowClient["name"])."' value='".htmlentities($rowClient["name"])."'>".htmlentities($rowClient["name"])."</option>";

			  }			

			echo "</select>";

    	?><input type="hidden" name="careerlevelTemp" id="careerlevelTemp" value="<?php echo htmlentities(urldecode($row["careerlevel"])); ?>" />

    </td>

</tr>   

<tr><td>Required Nationality</td>

    <td><input type="text" name="nationality" size="50" value="<?php echo htmlentities(urldecode($row["nationality"])); ?>"/></td>

</tr>    

    <tr>

	<td>Status</td>

    <td>

    	<?php

			$query = "SELECT * FROM `jobstatus` order by sort";

			$result = @$connection->query($query);

			$error_status = @$connection->errorInfo();

			if(isset($error_status[2])){

				$connection = null;

				unset($connection);

				exit;

			}

			echo "<select name='status' id='status' ><option></option>";

			while($rowStatus = @$result->fetch())

			  {

			  echo "<option id='".htmlentities($rowStatus["name"])."' value='".htmlentities($rowStatus["name"])."'>".htmlentities($rowStatus["name"])."</option>";

			  }			

			echo "</select>";

    	?><input type="hidden" name="tempStatus"  id="tempStatus" value="<?php echo htmlentities($row["status"]); ?>"/>
          <input type="hidden" name="originalStatus"  id="originalStatus" value="<?php echo htmlentities($row["status"]); ?>"/>
          
           <input type='hidden' id='Ogstatuschangedate' name='Ogstatuschangedate' value='<?php echo htmlentities(urldecode($row['statuschangedate'])); ?>'/>

          <input type='hidden' id='statuschangedate' name='statuschangedate' value='<?php echo htmlentities(urldecode($row['statuschangedate'])); ?>'/>

    </td>

</tr>

    <tr><td>Consultant</td>

        <td>

            <?php

			$query = "SELECT * FROM `consultant`;";

			$result = @$connection->query($query);	

			$error_status = @$result->errorInfo();

			if(isset($error_status[2])){

				$connection = null;

				unset($connection);

				exit;

			}

			echo "<select name='consultant' id='consultant' ><option></option>";

			while($rowConsultant = @$result->fetch())

			  {

                $fname = $rowConsultant["fname"];

                echo $fname;

			  echo "<option id='".htmlentities($fname)."' value='".htmlentities($fname)."'>".htmlentities($fname)."</option>";

			  }			

			echo "</select>";

    	?>

            <input type="hidden" name="tempConsultant"  id="tempConsultant" value="<?php echo htmlentities($row["consultant"]); ?>"/>

        </td>

    </tr>

    <tr>

    <td class='style5'>Notes</td><td><textarea name='notes' id='notes' rows='10' cols='60'><?php echo  htmlentities(urldecode($row["notes"])); ?></textarea></td>
    </tr> 

    <tr>
    	<td>Placed Candidate:</td>
    	<td>
    		<input type="text" id="placedcandidateRef" name="placedcandidateRef" size="25" value="<?php echo htmlentities(urldecode($row["placedcandidateRef"])); ?>" placeholder="Candidate Ref"/>
    		<input type="button" id="lookupcandidate" name="lookupcandidate" value="Check Candidate">
    		<label id="placedcandidatename" style="font-size: 14px;font-weight: bold;color: red;"></label>
    	</td>
    </tr>
    <tr>
    	<td>Job Start Date:</td>
    	<td><input type="text" id="jobstartdate" name="jobstartdate" size="50" value="<?php echo htmlentities(urldecode($row["jobstartdate"])); ?>"/>
    		<label>YYYY-MM-DD</label>
    	</td>
    </tr>
     <tr>
    	<td>Invoice Amount:</td>
    </tr>
    <tr>
    	<td>ZWL/RAND:</td>
    	<td><input type="text" name="invoiceamount" size="50" value="<?php echo htmlentities(urldecode($row["invoiceamount"])); ?>"/></td>
    </tr>
     <tr>
    	<td>USD:</td>
    	<td><input type="text" name="invoiceamountUSD" size="50" value="<?php echo htmlentities(urldecode($row["invoiceamountUSD"])); ?>"/></td>
    </tr>
     <tr>
    	<td>Placed:</td>
    	<td>
    		<select id="placed" name="placed">
               <option value="0" <?php echo ($row["placed"] == 0) ? 'selected="selected"' : ''; ?>>No</option>
               <option value="1" <?php echo ($row["placed"] == 1) ? 'selected="selected"' : ''; ?>>Yes</option>         
            </select>
    	</td>
    </tr>
    
     <tr>
    	<td>References Done:</td>
    	<td>
    		<select id="referencesdone" name="referencesdone">
               <option value="0" <?php echo ($row["referencesdone"] == 0) ? 'selected="selected"' : ''; ?>>No</option>
               <option value="1" <?php echo ($row["referencesdone"] == 1) ? 'selected="selected"' : ''; ?>>Yes</option>         
            </select>
    	</td>
    </tr>

    <tr>
    	<td>Advertise on Website:</td>
    	<td>
    		<select id="showjob" name="showjob">
               <option value="0" <?php echo ($row["showjob"] == 0) ? 'selected="selected"' : ''; ?>>No</option>
               <option value="1" <?php echo ($row["showjob"] == 1) ? 'selected="selected"' : ''; ?>>Yes</option>         
            </select>
    	</td>
    </tr>

    <tr>

        <td class='style5'>Hot Job</td><td><select id="hotind" name="hotind">

                <option id="0">No</option>

                <option id="1">Yes</option>           

            </select>

            <input type="hidden" name="temphotind"  id="temphotind" value="<?php echo htmlentities($row["hotind"]); ?>"/>

        </td></tr>

<tr>

	<td colspan="2" align="center"><input id="savejob" type="submit" value="Save Job" />
	<img id="processingloader" src='http://recruitmentmatters.co.zw/images/loader.gif' style="height: 150px;display: none;" />
	</td>

</tr> 
 
</table>

	

<tr><td>Facebook Link Address: </td>

        <td><u>http://recruitmentmatters.co.zw/jobDetail_F.php?fb_jid=<?php

            echo htmlentities($jobID);

        ?></u></tr><br></br>

<tr><td>New Website Facebook Link Address: </td>

        <td><u>http://recruitmentmatters.co.zw/job.php?rmjid=<?php

            echo htmlentities($jobID); 

        ?></u></tr>

<tr>

<br><br>

        </form>
        </div>  <!-- close job details tab -->

        <div id="allocatedcandidates" class="tabcontent">
        	
        	    <form action='getCandidate.php' method='post' name='search'>

		<center><h3>Allocated Candidates<br>

			<table width="200" style="font: normal 12px/150% Arial, Helvetica, sans-serif;border: 1px solid black; ">

				<tr align="left"><th style="background-color: #FCDADB">Candidate</th><th style="background-color: #FCDADB">Go to Record</th></tr>

				<?php

					while($can2jobRow = @$can2jobR->fetch()){

						$candidateName = $can2jobRow["candidateName"];

						$candidateName = urldecode($candidateName);

						echo "<tr>";

						echo "<td>".htmlentities($candidateName)."</td>";

						echo "<td><button  type='button' onclick='getCandidate(".htmlentities($can2jobRow['idCan']).")' name='edit'><img src='images/b_edit.png' width='16' height='16' /></TD>";

						echo "</tr>";

					}

				?>

			</table>

		</center>

	</form>

        </div><!-- close allocatedcandidates tab -->

        <div id="jobapplicants" class="tabcontent">

			<p><center><span style="font: normal 12px/150% Arial, Helvetica, sans-serif;"><h3>Job Applicants<h3></center>
			 <table>
			  		<tr>
			<td>
			 <?php
				$sqlja = "SELECT * FROM `jobapplicants` WHERE `jobid`=".$jobID;
				$resultja = @$connection->query($sqlja);
				$error_status = @$connection->errorInfo();
				if(isset($error_status[2])){
					$connection = null;
					unset($connection);
					exit;
				}
				?>
				<style scoped >
					.green{color: green;cursor: pointer;}
					.red{color: red;cursor: pointer;}
				</style>
				<table align="center" width="800" style="font: normal 12px/150% Arial, Helvetica, sans-serif;border: 1px solid black; ">
					<th style="background-color: #FCDADB">Candidate Ref</th>
					<th style="background-color: #FCDADB">First Name</th>
					<th style="background-color: #FCDADB">Surname</th>
					<th style="background-color: #FCDADB">Category</th>
					<th style="background-color: #FCDADB"><table>
						<caption>Questions</caption>
				              	<th style="min-width:40px;">Q 1</th>
				              	<th style="min-width:40px;">Q 2</th>
				              	<th style="min-width:40px;">Q 3</th>
				              </table></th>
					<th style="background-color: #FCDADB">View</th>
					 <?php while($rowja = @$resultja->fetch()){ ?>
			           <tr>
			           	<td><?php echo $rowja["candidateRef"] ?></td>
			           	<td><?php echo $rowja["firstName"] ?></td>
			           	<td><?php echo $rowja["surname"] ?></td>
			           	<td><?php echo $rowja["categoryCode"] ?></td>
			           	<td>
			              <table>
			              <tr>
			              	<td align="center" style="min-width:40px;" title="<?php echo $rowja["q1"] ?>" ><span class="glyphicon glyphicon-<?php if ($rowja["a1"]=="ok") {echo "ok green";}else{echo "remove red";}?>"></span></td>
			              	<td align="center" style="min-width:40px;" title="<?php echo $rowja["q2"] ?>"><span class="glyphicon glyphicon-<?php if ($rowja["a2"]=="ok") {echo "ok green";}else{echo "remove red";}?>"></span></td>
			              	<td align="center" style="min-width:40px;" title="<?php echo $rowja["q3"] ?>"><span class="glyphicon glyphicon-<?php if ($rowja["a3"]=="ok") {echo "ok green";}else{echo "remove red";}?>"></span></td>
			              </tr>
			              </table>
			           	</td>
			           	<td><form action="getCandidate.php" method="post"><button  type='button' value='<?php echo $rowja["ID"]; ?>' name='edit'   onclick="getCandidate(<?php echo $rowja["ID"]; ?>);"  ><img src='images/b_edit.png' width='16' height='16' /></form>
			           	</td>
			           </tr>
					 <?php  }  $connection = null; unset($connection); ?>
				</table>
			</td>
			</tr>
			  	</table>

			 <br></br>
        </div>
</body>

</html>

<script type="text/javascript">

  $(function() {
  	
  	document.getElementById("defaultOpen").click();
  	   $("#jobstartdate").datepicker({ dateFormat: 'yy-mm-dd' });
  	   $("#statuschangedate").datepicker({ dateFormat: 'yy-mm-dd' });

  	   if($("placedcandidateRef").val()!=""){

  	   	var placedcandidateRef=$('#placedcandidateRef').val();
         lookupCandidate(placedcandidateRef);
  	   	
  	   }


		$('#status').change(function() { 
            var today=new Date();
            
            if(hasStatusChanged()==true){
            	today=formatDate(today);
            	$("#statuschangedate").val(today);
            }else{
             var Ogstatuschangedate=$("#Ogstatuschangedate").val();
             $("#statuschangedate").val(Ogstatuschangedate);
            }

		 } );
       
  // 	   $("#savejob").submit(function( event ) {
             
  //            event.preventDefault();
  //            event.stopPropagation();
  //            $("#processingloader").show();
  //            $("#savejob").hide();
            
  //            //alert('waite');

  //            return;

		// });

		$("#lookupcandidate").click(function(event){
           
           if($("placedcandidateRef").val()==""){return;}

            var placedcandidateRef=$('#placedcandidateRef').val();
            lookupCandidate(placedcandidateRef);
            

		})

  	    $("#savejob").click(function( event ) {

             $("#processingloader").show();
             $("#savejob").hide();

		});

   });

  function hasStatusChanged(){

		var ogStatus=$("#originalStatus").val();
		var status=$("#status").val();

        hasChanged=false;

		if(status!=ogStatus){hasChanged=true;}

		return hasChanged;

	}

  function getCandidate(id){
  
    var path="getCandidate.php?edit="+id;
    window.open(path,"_blank");

   //var method = "post";
    //var path="getCandidate.php";

    //var form = document.createElement("form");
    //form.setAttribute("method", method);
    //form.setAttribute("action", path);

     //var hiddenField = document.createElement("input");
     //hiddenField.setAttribute("type", "text");
     //hiddenField.setAttribute("name", "edit");
     //hiddenField.setAttribute("value", id);

     //form.appendChild(hiddenField);

    //document.body.appendChild(form);
    //form.submit();

  }

  function lookupCandidate(placedcandidateRef){

  	var url = "jobs_backend.php";
    var method="get_candidatedetails";

    $.ajax({
    url: url,
    type: "POST",
    dataType: 'json',
    data: {'candidateRef': placedcandidateRef,'method': method},
    success: function (data) {
       
       if(data){
         //show candidate details
		 $("#placedcandidatename").text(data.firstName + ' ' + data.surname);
       }

    }
  });

  }

  function formatDate(date) {
	  var monthNames = [
	    "01", "02", "03",
	    "04", "05", "06", "07",
	    "08", "09", "10",
	    "11", "12"
	  ];

	  var day = date.getDate();
	  var monthIndex = date.getMonth();
	  var year = date.getFullYear();

	  return year + '-' + monthNames[monthIndex] + '-' + day;
	  //return year + '-' + monthIndex + '-' + day;

	}

</script>