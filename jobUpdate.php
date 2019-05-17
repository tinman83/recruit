<?php session_start(); ob_start();
    ini_set('max_execution_time', -1);
	if(!isset($_SESSION['Level_access']) || !isset($_SESSION['user_name']) || !($_SESSION['Level_access']==1)){

		$_SESSION = array();

		if($_COOKIE[session_name()]){

			setcookie(session_name(),'',time()-86400,'/');

		}

		session_destroy();

		header("Location: logon_F.php");

		exit;

	}

    @include('./new_includes_folder/connections.php');
    

	if(!($_POST)){

		header("Location: adminHome_F.php");

		exit;

	}

	$username=$_SESSION['user_name'];

	$category = @$_POST["category"];

	$title = @$_POST["title"];

	$location = @$_POST["location"];

	$countryId = @$_POST["country"];

	$salary = @$_POST["salary"];

	$startDate = @$_POST["startDate"];

	$desc = @$_POST["desc"];

	$client = @$_POST["client"];
    
    $originalStatus = @$_POST["originalStatus"];

	$status = @$_POST["status"];

	$jobID = @$_POST["jobID"];

	$skill = @$_POST["skill"];

	$qual = @$_POST["qual"];	

    $consultant = @$_POST["consultant"];

    $notes = @$_POST["notes"];

    $hotind = @$_POST["hotind"];

    $nationality = @$_POST["nationality"];

    $careerlevel = @$_POST["careerlevel"];

    $gender = @$_POST["gender"];

    $degree = @$_POST["degree"];

    $tag1 = @$_POST["tag1"];

    $tag2 = @$_POST["tag2"];

    $tag3 = @$_POST["tag3"];

    $tag4 = @$_POST["tag4"];

    $tag5 = @$_POST["tag5"];

    $expDetail = @$_POST["expDetail"];

    $explevel = @$_POST["explevel"];

    $summary = @$_POST["summary"];

    $companyType = @$_POST["companyType"];

    $jobtype = @$_POST["jobtype"];

    $qxn1 = @$_POST["qxn1"];
    $qxn2 = @$_POST["qxn2"];
    $qxn3 = @$_POST["qxn3"];


    // $ans1 = (@$_POST["ans1"] == 1) ? true : false;
    // $ans2 = (@$_POST["ans2"] == 1) ? true : false;
    // $ans3 = (@$_POST["ans3"] == 1) ? true : false;

    $ans1 = (@$_POST["ans1"] == 1) ? 1 : 0;
    $ans2 = (@$_POST["ans2"] == 1) ? 1 : 0;
    $ans3 = (@$_POST["ans3"] == 1) ? 1 : 0;

    $locationdescription = @$_POST["locationdescription"];
    $placedcandidateRef = @$_POST["placedcandidateRef"];
    $jobstartdate = (@$_POST["jobstartdate"] == "") ? "1900-01-01" : @$_POST["jobstartdate"];
    $invoiceamount = (@$_POST["invoiceamount"] == "") ? 0.00 : @$_POST["invoiceamount"];
    $invoiceamountUSD = (@$_POST["invoiceamountUSD"] == "") ? 0.00 : @$_POST["invoiceamountUSD"];
    $showjob = intval(@$_POST["showjob"]);
    $placed = intval(@$_POST["placed"]);
    $referencesdone = intval(@$_POST["referencesdone"]);
    
    $statuschangedate = @$_POST["statuschangedate"];
   
    // $ans3 = (@$_POST["ans3"] == 1) ? true : false;

     $sendemails=0;
   	 $sequence='';

   	 if($originalStatus=='Open' && $status!=$originalStatus && $status!='On Hold'){
       $sendemails=1;
   	   $sequence='normal';
     }else if( ($originalStatus!=$status) && $status=='On Hold'){
   	   $sendemails=1;
   	   $sequence='onhold';
     }

  
	$query = "UPDATE `dawn_recruitment`.`job` SET `title` = ?,location= ?,countryId=?,salary = ?, description = ?, startDate = ?,category = ?, status = ?, client = ?, keySkills = ?, consultant = ?,hotind = ?,nationality = ?,empType = ?,type = ?,careerlevel = ?,gender = ?,degree = ?,tag1 = ?,tag2 = ?,tag3 = ?,tag4 = ?,tag5 = ?,expDetail = ?,explevel = ?,summary = ?, qual = ?, notes = ?,q1=?,q2=?,q3=?,a1=?,a2=?,a3=?,updateDate=CURDATE(),sendemails=?,sequence=?,locationdescription=?,placedcandidateRef=?,jobstartdate=?,invoiceamount=?,invoiceamountUSD=?,showjob=?,placed=?,referencesdone=?,statuschangedate=? WHERE id = ?";

	$firing_statement = @$connection->prepare($query);

	@$firing_statement->execute([$title,$location,$countryId,$salary,$desc,$startDate,$category,$status,$client,$skill,$consultant,$hotind,$nationality,$jobtype,$companyType,$careerlevel,$gender,$degree,$tag1,$tag2,$tag3,$tag4,$tag5,$expDetail,$explevel,$summary,$qual,$notes,$qxn1,$qxn2,$qxn3,$ans1,$ans2,$ans3,$sendemails,$sequence,$locationdescription,$placedcandidateRef,$jobstartdate,$invoiceamount,$invoiceamountUSD,$showjob,$placed,$referencesdone,$statuschangedate,$jobID]);
   
	$error_status = @$firing_statement->errorInfo();

	if(isset($error_status[2])){

		$connection = null;

		unset($connection);

		exit;

	}


    //UPDATE CANDIDATE PLACEMENT DETAILS
    if($placedcandidateRef!=''){

	    $query="UPDATE  dawn_recruitment.personal SET placedWith=?,jobTitle=?,jobStartDate=?,
	     updatedByUsername=?,canStatus='Placed',updateDate=CURDATE(),updateTimeStamp=NOW() WHERE personal.candidateRef = ?";

		$firing_statement = @$connection->prepare($query);
		@$firing_statement->execute([$client,$title,$jobstartdate,$username,$placedcandidateRef]);
		$error_status = @$firing_statement->errorInfo();
		if(isset($error_status[2])){
			$connection = null;
			unset($connection);
			exit;
		}


    } //////


	$connection=null;	

	try {

		$connection = new PDO("mysql:host=localhost;dbname=dawn_jobs",'dawn_grant','we1come');

	}

	catch(PDOException $e){

		exit;

	}

    
    //update the jobs database as well

	$query = "UPDATE `dawn_jobs`.`job` SET `title` = ?,location= ?,countryId= ?,salary = ?, description = ?, startDate = ?,category = ?, status = ?, keySkills = ?, consultant = ?,hotind = ?,nationality = ?,empType = ?,type = ?,careerlevel = ?,gender = ?,degree = ?,tag1 = ?,tag2 = ?,tag3 = ?,tag4 = ?,tag5 = ?,expDetail = ?,explevel = ?,summary = ?, qual = ?, updateDate=CURDATE() WHERE id = ?";        

    $firing_statement = @$connection->prepare($query);

	@$firing_statement->execute([$title,$location,$countryId,$salary,$desc,$startDate,$category,$status,$skill,$consultant,$hotind,$nationality,$jobtype,$companyType,$careerlevel,$gender,$degree,$tag1,$tag2,$tag3,$tag4,$tag5,$expDetail,$explevel,$summary,$qual,$jobID]);

	$error_status = @$firing_statement->errorInfo();

	if(isset($error_status[2])){

		$connection = null;

		unset($connection);

		exit;

	}


	echo "<meta http-equiv='refresh' content='0; url=jobSearch_F.php'>";	

?>	