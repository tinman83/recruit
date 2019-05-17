<?php 

	 @include('./new_includes_folder/connections.php');
     
     function getUtilDate(){
        
        $reportDay=new DateTime();
        $day=date("D");

        if($day=='Sat'){
            $date = new DateTime(); 
            $date->modify("-1 day");
            $reportDay=$date;
        }else if($day=='Sun'){

            $date = new DateTime(); 
            $date->modify("-2 day");
            $reportDay=$date;

        }else{
            $reportDay=new DateTime();
        }

        return $reportDay;

    }

    function isWorkingDay($date){

    	$dayOfWeek=$date->format("D");

    	if($dayOfWeek=="Sat"){
          return false;
    	}else if($dayOfWeek=="Sun"){
          return false;
    	}else{
          return true;
    	}

    }

    function get_reports_list(){

       global $connection;

       $query ="SELECT * FROM reports";
       $result = @$connection->query($query);
       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }
        $rows=@$result->fetchAll();
        return $rows;
   }

     function get_countries_list(){

       global $connection;

       $query ="SELECT * FROM countries";
       $result = @$connection->query($query);
       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }
        $rows=@$result->fetchAll();
        return $rows;
   }

   function get_consultants_list(){

       global $connection;

       $query ="SELECT * FROM consultant WHERE isactive=1";
       $result = @$connection->query($query);
       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }
        $rows=@$result->fetchAll();
        return $rows;
   }

    function get_consultant_jobs_posted($reportYear){

       global $connection;

       $query ="SELECT jobsp.postedYear,jobsp.consultantname,
                SUM(jobsp.`Jan`) AS Jan,
                SUM(jobsp.`Feb`) AS Feb,
                SUM(jobsp.`Mar`) AS Mar,
                SUM(jobsp.`Apr`) AS Apr,
                SUM(jobsp.`May`) AS May,
                SUM(jobsp.`Jun`) AS Jun,
                SUM(jobsp.`Jul`) AS Jul,
                SUM(jobsp.`Aug`) AS Aug,
                SUM(jobsp.`Sep`) AS Sep,
                SUM(jobsp.`Oct`) AS Oct,
                SUM(jobsp.`Nov`) AS Nov,
                SUM(jobsp.`Dec`) AS `Dec`
                FROM (
                SELECT consultantname,postedYear,postedMonth,
                GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 1),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jan`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 2),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Feb`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 3),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Mar`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 4),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Apr`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 5),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `May`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 6),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jun`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 7),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jul`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 8),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Aug`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 9),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Sep`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 10),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Oct`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 11),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Nov`,
        GROUP_CONCAT(IF((CONVERT(`j`.`postedMonth`,UNSIGNED INTEGER) = 12),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Dec`
                FROM (
                SELECT CONCAT(fname,' ',sname) AS consultantname,
                Year(postedDate) AS postedYear,
                MONTH(postedDate) as postedMonth,
                count(job.id) AS noOfJobs 
                FROM job
                JOIN consultant c ON c.fname=job.consultant
                WHERE consultant<>'' AND c.isactive=1 AND Year(postedDate)=$reportYear
                GROUP BY CONCAT(fname,' ',sname),Year(postedDate),MONTH(postedDate)
                WITH ROLLUP) j
                GROUP BY consultantname,postedYear,postedMonth

                ) AS jobsp
                WHERE postedYear is not null
                GROUP BY consultantname,postedYear";

       $result = @$connection->query($query);

       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }

        $rows=@$result->fetchAll();

        return $rows;


   }

    function get_jobs_placements_by_country($reportYear){

       global $connection;

       $query ="SELECT jobsp.startedYear,jobsp.countryname,
                SUM(jobsp.`Jan`) AS Jan,
                SUM(jobsp.`Feb`) AS Feb,
                SUM(jobsp.`Mar`) AS Mar,
                SUM(jobsp.`Apr`) AS Apr,
                SUM(jobsp.`May`) AS May,
                SUM(jobsp.`Jun`) AS Jun,
                SUM(jobsp.`Jul`) AS Jul,
                SUM(jobsp.`Aug`) AS Aug,
                SUM(jobsp.`Sep`) AS Sep,
                SUM(jobsp.`Oct`) AS Oct,
                SUM(jobsp.`Nov`) AS Nov,
                SUM(jobsp.`Dec`) AS `Dec`
                FROM (
                SELECT countryname,startedYear,startedMonth,
                GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 1),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jan`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 2),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Feb`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 3),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Mar`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 4),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Apr`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 5),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `May`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 6),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jun`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 7),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jul`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 8),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Aug`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 9),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Sep`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 10),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Oct`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 11),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Nov`,
        GROUP_CONCAT(IF((CONVERT(`j`.`startedMonth`,UNSIGNED INTEGER) = 12),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Dec`
                FROM (
                SELECT c.country_name AS countryname,Year(jobstartdate) AS startedYear,MONTH(jobstartdate) as  startedMonth,count(*) AS noOfJobs 
        FROM job
        LEFT JOIN countries c ON c.country_id=IFNULL(job.countryid,196)
        WHERE placed=1 AND Year(jobstartdate)=$reportYear
        GROUP BY c.country_name,Year(jobstartdate),MONTH(jobstartdate)
                WITH ROLLUP) j
                GROUP BY countryname,startedYear,startedMonth

                ) AS jobsp
                WHERE startedYear is not null
                GROUP BY countryname,startedYear";

       $result = @$connection->query($query);

       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }

        $rows=@$result->fetchAll();

        return $rows;


   }

   function get_consultant_monthly_target_placements($reportYear){

       global $connection;

       $query ="SELECT * FROM (SELECT jobsp.startedYear,jobsp.consultantname,1 as rowlevel,CONCAT('Total Amount',' ',IF(jobsp.countryid=162 ,'(RAND)', '(USD)')) as label,
                SUM(jobsp.`Jan`) AS Jan,
                SUM(jobsp.`Feb`) AS Feb,
                SUM(jobsp.`Mar`) AS Mar,
                SUM(jobsp.`Apr`) AS Apr,
                SUM(jobsp.`May`) AS May,
                SUM(jobsp.`Jun`) AS Jun,
                SUM(jobsp.`Jul`) AS Jul,
                SUM(jobsp.`Aug`) AS Aug,
                SUM(jobsp.`Sep`) AS Sep,
                SUM(jobsp.`Oct`) AS Oct,
                SUM(jobsp.`Nov`) AS Nov,
                SUM(jobsp.`Dec`) AS `Dec`
                FROM (
                SELECT consultantname,startedYear,startedMonth,target,countryid,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 1),`j`.`totalinvoiceamount`,0)SEPARATOR ',') AS `Jan`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 2),`j`.`totalinvoiceamount`,0)SEPARATOR ',') AS `Feb`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 3),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Mar`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 4),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Apr`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 5),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `May`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 6),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Jun`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 7),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Jul`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 8),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Aug`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 9),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Sep`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 10),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Oct`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 11),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Nov`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 12),`j`.`totalinvoiceamount`,0) SEPARATOR ',') AS `Dec`
                FROM (
                SELECT Year(jobstartdate) AS startedYear,CONCAT(fname,' ',sname) AS consultantname,c.countryid,
                MONTH(jobstartdate) as startedMonth,
                Sum(`invoiceamount`) AS totalinvoiceamount,
                c.monthlytarget AS target,(Sum(`invoiceamount`)/c.monthlytarget)*100 AS percentagetarget
                FROM job
                JOIN consultant c ON c.fname=job.consultant
                WHERE c.isactive=1 AND CONCAT(fname,' ',sname) is not null AND Year(jobstartdate)=$reportYear
                GROUP BY CONCAT(fname,' ',sname),Year(jobstartdate),CONCAT(c.fname,' ',c.sname),MONTH(jobstartdate)
                WITH ROLLUP) j
                GROUP BY consultantname,startedYear,startedMonth

                ) AS jobsp
                WHERE startedYear is not null AND consultantname is not null
                GROUP BY consultantname,startedYear
UNION 
SELECT jobsp.startedYear,jobsp.consultantname,2 as rowlevel,CONCAT('% of ',IF(jobsp.countryid=162 ,'R', '$'),ROUND(target,2)) as label,
                SUM(jobsp.`Jan`) AS Jan,
                SUM(jobsp.`Feb`) AS Feb,
                SUM(jobsp.`Mar`) AS Mar,
                SUM(jobsp.`Apr`) AS Apr,
                SUM(jobsp.`May`) AS May,
                SUM(jobsp.`Jun`) AS Jun,
                SUM(jobsp.`Jul`) AS Jul,
                SUM(jobsp.`Aug`) AS Aug,
                SUM(jobsp.`Sep`) AS Sep,
                SUM(jobsp.`Oct`) AS Oct,
                SUM(jobsp.`Nov`) AS Nov,
                SUM(jobsp.`Dec`) AS `Dec`
                FROM (
                SELECT consultantname,startedYear,startedMonth,target,countryid,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 1),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Jan`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 2),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Feb`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 3),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Mar`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 4),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Apr`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 5),`j`.`percentagetarget`,0) SEPARATOR ',') AS `May`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 6),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Jun`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 7),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Jul`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 8),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Aug`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 9),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Sep`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 10),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Oct`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 11),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Nov`,
                   GROUP_CONCAT(IF((`j`.`startedMonth` = 12),`j`.`percentagetarget`,0) SEPARATOR ',') AS `Dec`
                FROM (
                SELECT Year(jobstartdate) AS startedYear,CONCAT(fname,' ',sname) AS consultantname,c.countryid,
                MONTH(jobstartdate) as startedMonth,
                Sum(`invoiceamount`) AS totalinvoiceamount,
                c.monthlytarget AS target,ROUND((Sum(`invoiceamount`)/c.monthlytarget)*100,2) AS percentagetarget
                FROM job
                JOIN consultant c ON c.fname=job.consultant
                WHERE c.isactive=1 AND CONCAT(fname,' ',sname) is not null AND Year(jobstartdate)=$reportYear 
                GROUP BY CONCAT(fname,' ',sname),Year(jobstartdate),CONCAT(c.fname,' ',c.sname),MONTH(jobstartdate)
                WITH ROLLUP) j
                GROUP BY consultantname,startedYear,startedMonth

                ) AS jobsp
                WHERE jobsp.startedYear is not null AND jobsp.consultantname is not null
                GROUP BY consultantname,startedYear) AS consultantkpi
                ORDER BY consultantname,consultantkpi.rowlevel";

       $result = @$connection->query($query);

       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }

        $rows=@$result->fetchAll();

        return $rows;


   }


   function get_jobs_converted_to_status_by_consultant($reportYear){

       global $connection;

       $query ="SELECT jobsp.statusYear,jobsp.consultantname,jobsp.`status`,
                SUM(jobsp.`Jan`) AS Jan,
                SUM(jobsp.`Feb`) AS Feb,
                SUM(jobsp.`Mar`) AS Mar,
                SUM(jobsp.`Apr`) AS Apr,
                SUM(jobsp.`May`) AS May,
                SUM(jobsp.`Jun`) AS Jun,
                SUM(jobsp.`Jul`) AS Jul,
                SUM(jobsp.`Aug`) AS Aug,
                SUM(jobsp.`Sep`) AS Sep,
                SUM(jobsp.`Oct`) AS Oct,
                SUM(jobsp.`Nov`) AS Nov,
                SUM(jobsp.`Dec`) AS `Dec`
                FROM (
                SELECT consultantname,`status`,`sort`,statusYear,statusMonth,
                GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 1),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jan`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 2),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Feb`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 3),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Mar`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 4),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Apr`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 5),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `May`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 6),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jun`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 7),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Jul`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 8),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Aug`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 9),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Sep`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 10),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Oct`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 11),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Nov`,
        GROUP_CONCAT(IF((CONVERT(`j`.`statusMonth`,UNSIGNED INTEGER) = 12),
                `j`.`noOfJobs`,0)
            SEPARATOR ',') AS `Dec`
                FROM (
                SELECT Year(j.statuschangedate) AS statusYear,CONCAT(c.fname,' ',c.sname) AS consultantname,
                js.`name` AS `status`, MONTH(j.statuschangedate) as statusMonth,count(*) AS noOfJobs,js.sort 
                FROM jobstatus js
                LEFT JOIN job j ON j.`status`=js.`name`
                JOIN consultant c ON c.fname=j.consultant
                WHERE c.isactive=1 AND Year(statuschangedate)=$reportYear 
                GROUP BY Year(statuschangedate),CONCAT(c.fname,' ',c.sname),j.`status`,MONTH(statuschangedate)
                WITH ROLLUP) j
                GROUP BY consultantname,`status`,statusYear,statusMonth

                ) AS jobsp
                WHERE statusYear is not null AND consultantname is not null
                GROUP BY consultantname,`status`,statusYear
                ORDER BY consultantname,`sort`,statusMonth";

       $result = @$connection->query($query);

       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }

        $rows=@$result->fetchAll();

        return $rows;


   }

    function get_consultant_jobs_posted_detail($reportYear,$reportMonth){

       global $connection;

       $query ="select CONCAT(c.fname,' ',c.sname) AS consultantname,
                j.postedDate,j.title,j.`status`,j.`client`
                FROM job j
                LEFT JOIN consultant c ON c.fname=j.consultant
                WHERE Year(postedDate)=$reportYear AND Month(postedDate)=$reportMonth
                ORDER BY DATE(j.postedDate)";

       $result = @$connection->query($query);

       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }

        $rows=@$result->fetchAll();

        return $rows;


   }

   function get_job_placement_details_by_country($reportCountryId,$reportYear,$reportMonth){

       global $connection;

       $query ="SELECT CONCAT(c.fname,' ',c.sname) AS consultantname,j.jobstartdate,j.title,j.invoiceamount,j.invoiceamountUSD,j.`status`,p.candidateRef,CONCAT(p.firstName,' ',p.surname) AS placedcandidatename,j.`client` 
            FROM job j
            LEFT JOIN personal p ON p.candidateRef=j.placedcandidateRef
            LEFT JOIN consultant c ON c.fname=j.consultant
            LEFT JOIN countries country ON country.country_id=IFNULL(j.countryid,196)
            WHERE placed=1 AND Year(j.jobstartdate)=$reportYear AND Month(j.jobstartdate)=$reportMonth AND j.countryid=$reportCountryId ORDER BY j.jobstartdate";

       $result = @$connection->query($query);

       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }

        $rows=@$result->fetchAll();

        return $rows;


   }

   function get_jobs_converted_to_status_detail($reportYear,$reportMonth){

       global $connection;

       $query ="select CONCAT(c.fname,' ',c.sname) AS consultantname,
                j.statuschangedate,j.title,j.`status`,j.`client`
                FROM job j
                LEFT JOIN consultant c ON c.fname=j.consultant
                WHERE Year(statuschangedate)=$reportYear AND Month(statuschangedate)=$reportMonth
                ORDER BY j.statuschangedate DESC";

       $result = @$connection->query($query);

       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }

        $rows=@$result->fetchAll();

        return $rows;


   }

   function get_monthly_target_placement_details_by_consultant($consultant,$reportYear,$reportMonth){

       global $connection;

       $query ="SELECT CONCAT(c.fname,' ',c.sname) AS consultantname,j.jobstartdate,j.title,j.invoiceamount,j.invoiceamountUSD,j.`status`,p.candidateRef,CONCAT(p.firstName,' ',p.surname) AS placedcandidatename,j.`client` 
            FROM job j
            LEFT JOIN personal p ON p.candidateRef=j.placedcandidateRef
            LEFT JOIN consultant c ON c.fname=j.consultant
            LEFT JOIN countries country ON country.country_id=IFNULL(j.countryid,196)
            WHERE Year(j.jobstartdate)=$reportYear AND Month(j.jobstartdate)=$reportMonth AND c.fname='".$consultant."' ORDER BY j.jobstartdate";

       $result = @$connection->query($query);

       $error_status = @$connection->errorInfo();
       if(isset($error_status[2])){
          $connection = null;
          unset($connection);
          exit;
        }

        $rows=@$result->fetchAll();

        return $rows;


   }



	  function return_result($result)
	  {
	    echo $result;     
	    exit();
	  }

	/////////////////////////////////////////////////////
	  // section that handle all ajax request from the page  //
	  /////////////////////////////////////////////////////
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'){
        
        if($_POST['reportId'] == 'getReportsList')
        {

          $rows=get_reports_list();

            return_result(json_encode($rows));

        }

        if($_POST['reportId'] == 'getCountriesList')
        {

          $rows=get_countries_list();

            return_result(json_encode($rows));

        }

        if($_POST['reportId'] == 'getConsultantsList')
        {

          $rows=get_consultants_list();

            return_result(json_encode($rows));

        }

		if($_POST['reportId'] == 'totaljobsposted')
	    {

          $reportYear=$_POST['reportYear'];

          $rows=get_consultant_jobs_posted($reportYear);

	        return_result(json_encode($rows));

	    }

      if($_POST['reportId'] == 'totaljobplacementsbycountry')
      {

          $reportYear=$_POST['reportYear'];

          $rows=get_jobs_placements_by_country($reportYear);

          return_result(json_encode($rows));

      }

      if($_POST['reportId'] == 'consultantmonthlytargetplacements')
      {

          $reportYear=$_POST['reportYear'];

          $rows=get_consultant_monthly_target_placements($reportYear);

          return_result(json_encode($rows));

      }

       if($_POST['reportId'] == 'totaljobsconvertedtostatusbyconsultant')
      {

          $reportYear=$_POST['reportYear'];

          $rows=get_jobs_converted_to_status_by_consultant($reportYear);

          return_result(json_encode($rows));

      }

      if($_POST['reportId'] == 'jobsposteddetail')
        {

          $reportYear=$_POST['reportYear'];
          $reportMonth=$_POST['reportMonth'];

          $rows=get_consultant_jobs_posted_detail($reportYear,$reportMonth);

          return_result(json_encode($rows));

        }

        if($_POST['reportId'] == 'jobplacementdetailsbycountry')
        {
          
          $reportCountryId=$_POST['reportCountryId'];
          $reportYear=$_POST['reportYear'];
          $reportMonth=$_POST['reportMonth'];

          $rows=get_job_placement_details_by_country($reportCountryId,$reportYear,$reportMonth);

          return_result(json_encode($rows));

        }
        
        if($_POST['reportId'] == 'totaljobsconvertedtostatusdetails')
        {
          
          $reportYear=$_POST['reportYear'];
          $reportMonth=$_POST['reportMonth'];

          $rows=get_jobs_converted_to_status_detail($reportYear,$reportMonth);

          return_result(json_encode($rows));

        }

        if($_POST['reportId'] == 'consultantmonthlytargetplacementsdetail')
        {
          
          $reportConsultant=$_POST['reportConsultant'];
          $reportYear=$_POST['reportYear'];
          $reportMonth=$_POST['reportMonth'];

          $rows=get_monthly_target_placement_details_by_consultant($reportConsultant,$reportYear,$reportMonth);

          return_result(json_encode($rows));

        }

	   

     

      
       
      
	    

	 }

?>