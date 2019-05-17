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
?>
<?php @include("adminHeader.php"); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Utility Dashboard</title>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" >
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>

<script src="reports/reportspresenter.js"></script>
<link href="css/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="css/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet">
<link href="css/plugins/datatables/responsive.dataTables.css" rel="stylesheet">

<script src="js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="js/plugins/datatables/dataTables.responsive.min.js"></script>

<script src="js/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="js/plugins/datatables/buttons.print.min.js"></script>

<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<link rel="stylesheet" href="css/plugins/datatables/buttons.dataTables.min.css" type="text/css">
<link rel="stylesheet" href="css/plugins/datatables/dataTables.tableTools.min.css" type="text/css">

<script type="text/javascript" src="reports/TotalJobsPostedReportPresenter.js"></script>
<script type="text/javascript" src="reports/TotalJobsPlacementsByCountryReportPresenter.js"></script>
<script type="text/javascript" src="reports/ConsultantMonthlyTargetPlacementsReportPresenter.js"></script>
<script type="text/javascript" src="reports/TotalJobsConvertedToStatusByConsultantReportPresenter.js"></script>
<script type="text/javascript" src="reports/JobsPostedDetailReportPresenter.js"></script>
<script type="text/javascript" src="reports/JobPlacementDetailsByCountryReportPresenter.js"></script>
<script type="text/javascript" src="reports/JobsPostedDetailReportPresenter.js"></script>
<script type="text/javascript" src="reports/JobsConvertedToStatusDetailReportPresenter.js"></script>
<script type="text/javascript" src="reports/ConsultantMonthlyTargetPlacementsDetailReportPresenter.js"></script>
<style type="text/css">
 #report-table thead th{
    border-top: 1px solid #111;
}
#report-table tr.group, tr.group:hover {
    background-color: #ddd;
    color: red;
    font-weight: bold;
}

#report-table td.subheader, td.subheader:hover {
    background-color: #fff;
    color: black;
}
</style>

</head>
<body>
<div class="container-fluid">
  <div class="report-layout row-fluid">
    <div class="layout-top row">
       <div id="param-container" class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
          <div class="row form-group form-group-sm">
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
              Report:<select class="form-control report_type" id="report-type" name="report-type">
                      <option value="">Select Report</option>
                    </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 report_country" style="display:none">
                  Country:<select class="form-control report-country" id="report-country" name="report-country">         </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 report_consultant" style="display:none">
                  Consultant:<select class="form-control report-consultant" id="report-consultant" name="report-consultant">         </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 report_start" style="display:none">
                Start Date:<input class="form-control datepicker" id="report-startdate" name="report-startdate" type="text" placeholder="Start Date">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 report_end" style="display:none">
                End Date:<input class="form-control" id="report-enddate" name="report-enddate" type="text" placeholder="End Date">
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 report_year">
                  Year:<select class="form-control report-year" id="report-year" name="report-year">         </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 report_month" style="display:none">
                Month:<select class="form-control" id="report-month" name="report-month">
                        <option value="">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                <font color="white">...</font><input type="button" id="btnsubmit" class="form-control btn btn-success" value="Submit">
            </div>
          </div>
		</div>
    </div>
    <div class="layout-body col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <img id="loadingSpinner" style="margin-left: 550px;display: none;" src="images/loader.gif">
         <div id="report_chart" class="report_chart" style="width: 100%; height:100%;"></div>
         <table id="report-table" class="display report_table" cellspacing="0" width="100%">
           <thead>
              <tr id="reporthead">
                
              </tr>
            </thead>
        </table>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(function(){
    
    var reportYear = $("#report-year");

    var date = new Date();
    var currentYear = date.getFullYear();
    
    var maxYear=(Number(currentYear)+3);
    
    for (var i = 2014; i < maxYear; i++) {
       reportYear.append($("<option />").val(i).text(i));
    }

    reportYear.val(currentYear);


    $('.report_type').on('change',function(e){
       var report_type = $('#report-type').val();
  
          switch (report_type){
            case 'totaljobsposted':
            case 'totaljobplacementsbycountry':
            case 'totaljobsconvertedtostatusbyconsultant':
            case 'consultantmonthlytargetplacements':
              showHideDateRange(0);     
              showHideYear(1);
              showHideChart(0);
              showHideMonth(0);
              showHideCountry(0);
              showHideConsultant(0);
            break;
            case 'jobsposteddetail':
            case 'totaljobsconvertedtostatusdetails':
              showHideDateRange(0);     
              showHideYear(1);
              showHideChart(0);
              showHideMonth(1);
              showHideCountry(0);
              showHideConsultant(0);
            break;
            case 'jobplacementdetailsbycountry':
              showHideDateRange(0);     
              showHideYear(1);
              showHideChart(0);
              showHideMonth(1);
              showHideCountry(1);
              showHideConsultant(0);
            break;
            case 'consultantmonthlytargetplacementsdetail':
              showHideDateRange(0);     
              showHideYear(1);
              showHideChart(0);
              showHideMonth(1);
              showHideCountry(0);
              showHideConsultant(1);
            break;
            default:
              showHideDateRange();     
              showHideYear(1);
              showHideChart(0);
              showHideMonth(0);
              showHideCountry(0);
              showHideConsultant(0);
            break;
            
          }     
    });

    resizeElements();

    $(window).on('resize', function(){
       
     resizeElements();
        
    });


  });
  function resizeElements(){

       var winHeight=$( window ).height();
       var height;
       if(winHeight>800){
           height=winHeight-200;
       }else{
           height=winHeight-180;
       }
       
       $('.layout-body').css('height', height+'px'); //set max height
       $('.report_chart').css('height', (height-10)+'px'); //set max height

  }

  function showHideDateRange(show) {
    if (show) {

        if ($(".report_start").filter(":hidden")) {
            $(".report_start").show();
        }
        if ($(".report_end").filter(":hidden")) {
            $(".report_end").show();
        }

    } else {
        if ($(".report_start").filter(":visible")) {
            $(".report_start").hide();
        }
        if ($(".report_end").filter(":visible")) {              
            $(".report_end").hide();
        }
    }
  }

  function showHideCountry(show) {
    if (show) {

        if ($(".report_country").filter(":hidden")) {
            $(".report_country").show();
        }

    } else {
        if ($(".report_country").filter(":visible")) {
            $(".report_country").hide();
        }
    }
  }

  function showHideConsultant(show) {
    if (show) {

        if ($(".report_consultant").filter(":hidden")) {
            $(".report_consultant").show();
        }

    } else {
        if ($(".report_consultant").filter(":visible")) {
            $(".report_consultant").hide();
        }
    }
  }

  function showHideMonth(show) {
    if (show) {

        if ($(".report_month").filter(":hidden")) {
            $(".report_month").show();
        }

    } else {
        if ($(".report_month").filter(":visible")) {
            $(".report_month").hide();
        }
    }
  }
  function showHideYear(show) {
    if (show) {

        if ($(".report_year").filter(":hidden")) {
            $(".report_year").show();
        }

    } else {
        if ($(".report_year").filter(":visible")) {
            $(".report_year").hide();
        }
    }
  }

  function showHideChart(show) {
      
      if (show) {

          if ($(".report_chart").filter(":hidden")) {
              $(".report_chart").show();
          }
          if ($(".report_table").filter(":visible")) {
              $(".report_table").hide();
          }

      } else {
          if ($(".report_chart").filter(":visible")) {
              $(".report_chart").hide();
          }
          if ($(".report_table").filter(":hidden")) {
              $(".report_table").show();
          }
          if (typeof(chart) != "undefined"){
            chart.clearChart(); 
          }
              
      }

      if($.fn.DataTable.isDataTable( '#report-table' ) ) {
            
                $("#report-table").dataTable().fnDestroy();
         }
        $('#report-table tbody > tr').remove();
        $('#reporthead > th').remove();

  }


</script>

</body>
</html>