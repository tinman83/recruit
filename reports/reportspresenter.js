var baseUrl;
var chart;

$(function() {
    
    

    var currentReport=null;
    loadReports();
    loadCountries();
    loadConsultants();

    $(this).find('#btnsubmit').on('click', function() {
//      console.log('submitting');
         var reportId=$('#report-type').val();         
         if(reportId===''){
         	return;
         }

         $('#loadingSpinner').show();

         renderReport(reportId);
     });

});

function loadReports(){

       var api_url="reports_api.php";
       
       var post_data = {
        'reportId': 'getReportsList'
       };
       $.ajax({
            type: 'POST',
            url: api_url,
            data: post_data,
            dataType: 'json'
        })
        .done(function (data) {

            var listitems;
            $.each(data, function(key, value){
                listitems += '<option value=' + value.reportname + '>' + value.reportdisplayname + '</option>';
            });

            var $select = $('#report-type');
            $select.append(listitems);


            });

  }

  function loadCountries(){

       var api_url="reports_api.php";
       
       var post_data = {
        'reportId': 'getCountriesList'
       };
       $.ajax({
            type: 'POST',
            url: api_url,
            data: post_data,
            dataType: 'json'
        })
        .done(function (data) {

            var listitems;
            $.each(data, function(key, value){
                listitems += '<option value=' + value.country_id + '>' + value.country_name + '</option>';
            });

            var $select = $('#report-country');
            $select.append(listitems);
            
            $select.val(196);

            });

  }

  function loadConsultants(){

       var api_url="reports_api.php";
       
       var post_data = {
        'reportId': 'getConsultantsList'
       };
       $.ajax({
            type: 'POST',
            url: api_url,
            data: post_data,
            dataType: 'json'
        })
        .done(function (data) {

            var listitems;
            $.each(data, function(key, value){
                listitems += '<option value=' + value.fname + '>' + value.fname +" "+ value.sname + '</option>';
            });

            var $select = $('#report-consultant');
            $select.append(listitems);

            });

  }

  function renderReport(reportid) {

		// Hide whatever report is currently shown.
            closeCurrentReport();

			switch(reportid){
	    		case 'totaljobsposted':{

                   var reportYear = $('#report-year').val();
	    			var totalJobsPostedReportPresenter=new TotalJobsPostedReportPresenter();
	    			totalJobsPostedReportPresenter.showReport(reportid,reportYear);
	    			break;
	    		}
          case 'totaljobplacementsbycountry':{

                    var reportYear = $('#report-year').val();
                    var totalJobsPlacementsByCountryReportPresenter=new TotalJobsPlacementsByCountryReportPresenter();
                    totalJobsPlacementsByCountryReportPresenter.showReport(reportid,reportYear);
                    break;
          } 
          case 'totaljobsconvertedtostatusbyconsultant':{

                    var reportYear = $('#report-year').val();
                    var totalJobsConvertedToStatusByConsultantReportPresenter=new TotalJobsConvertedToStatusByConsultantReportPresenter();
                    totalJobsConvertedToStatusByConsultantReportPresenter.showReport(reportid,reportYear);
                    break;
          } 
          case 'consultantmonthlytargetplacements':{

                    var reportYear = $('#report-year').val();
                    var consultantMonthlyTargetPlacementsReportPresenter=new ConsultantMonthlyTargetPlacementsReportPresenter();
                    consultantMonthlyTargetPlacementsReportPresenter.showReport(reportid,reportYear);
                    break;
          } 
          case 'jobsposteddetail':{

                   var reportYear = $('#report-year').val();
                   var reportMonth = $('#report-month').val();
                    var jobsPostedDetailReportPresenter=new JobsPostedDetailReportPresenter();
                    jobsPostedDetailReportPresenter.showReport(reportid,reportYear,reportMonth);
                    break;
            }
         case 'jobplacementdetailsbycountry':{
                    
                    var reportCountry = $('#report-country').val();
                    var reportYear = $('#report-year').val();
                    var reportMonth = $('#report-month').val();
                    var jobPlacementDetailsByCountryReportPresenter=new JobPlacementDetailsByCountryReportPresenter();
                    jobPlacementDetailsByCountryReportPresenter.showReport(reportid,reportCountry,reportYear,reportMonth);
                    break;
          } 
          case 'totaljobsconvertedtostatusdetails':{

                   var reportYear = $('#report-year').val();
                   var reportMonth = $('#report-month').val();
                    var jobsConvertedToStatusDetailReportPresenter=new JobsConvertedToStatusDetailReportPresenter();
                    jobsConvertedToStatusDetailReportPresenter.showReport(reportid,reportYear,reportMonth);
                    break;
            }
          case 'consultantmonthlytargetplacementsdetail':{
                    
                    var reportConsultant = $('#report-consultant').val();
                    var reportYear = $('#report-year').val();
                    var reportMonth = $('#report-month').val();
                    var consultantMonthlyTargetPlacementsDetailReportPresenter=new ConsultantMonthlyTargetPlacementsDetailReportPresenter();
                    consultantMonthlyTargetPlacementsDetailReportPresenter.showReport(reportid,reportConsultant,reportYear,reportMonth);
                    break;
          } 
    		default: {
                 $('#loadingSpinner').hide();
                 return false;
            }
	    	}
	
	}

	function closeCurrentReport(){
        

		if($.fn.DataTable.isDataTable( '#report-table' ) ) {

                $("#report-table").dataTable().fnDestroy();
         }

        $('#report-table tbody > tr').remove();
        $('#reporthead > th').remove();
        $('#report-table > thead').remove();

	}
