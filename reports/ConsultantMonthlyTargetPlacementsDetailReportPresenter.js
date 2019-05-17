
function ConsultantMonthlyTargetPlacementsDetailReportPresenter(){
    
    
    function init(){

    }

	var publicInt={

         showReport:function(reportId,reportConsultant,reportYear,reportMonth) {

               var api_url="reports_api.php";
               var post_data = {
                'reportId': reportId,
                'reportYear': reportYear,
                'reportMonth': reportMonth,
                'reportConsultant':reportConsultant
               };
           

               $.ajax({
                    type: 'POST',
                    url: api_url,
                    data: post_data,
                    dataType: 'json'
                })
                .done(function (data) {

                    publicInt.buildReportBody(data);

                });

	    },
      buildReportHead: function(data){
             
         },
	    buildReportBody: function(data){
            
            if($.fn.DataTable.isDataTable( '#report-table' ) ) {
                $("#report-table").dataTable().fnDestroy();
             }
            
            $('#report-table').DataTable( {
                        "data": data,
                        "searching":   false,
                        "info":     false,
                        "pageLength": 50,
                        "bLengthChange": false,
                        columns: [
                            { "data":"consultantname","title" : "Consultant", "orderable": true, "className": 'desktop' },
                            { "data":"title","title" : "Title", "orderable": true, "className": 'all' },
                            { "data":"status","title" : "Status", "orderable": true, "className": 'all' },
                            { "data":"jobstartdate","title" : "Start Date", "orderable": true, "className": 'all' },
                            { "data":"invoiceamount","title" : "Inv (ZWL/RAND)", "orderable": true, "className": 'all' },
                             { "data":"invoiceamountUSD","title" : "Inv (USD)", "orderable": true, "className": 'all' },
                            { "data":"placedcandidatename","title" : "Placed Candidate", "orderable": true, "className": 'all' },
                            { "data":"candidateRef","title" : "Candidate Ref", "orderable": true, "className": 'all' },
                            { "data":"client","title" : "Client", "orderable": true, "className": 'all' }
                         ],
                         "columnDefs": [
                            { "visible": false, "targets": 0 }
                        ],
                        responsive: true,
                        dom: 'Bfrtip',
                        buttons: [
                           {
                              extend: 'print',
                              title: "Monthly Target Placements Detail - " + $("#report-consultant option:selected").text() + " " + $("#report-month option:selected").text() + " " + $("#report-year").val()
                            },'excel', 'pdf'
                        ],
                        "order": [[0, 'asc' ]],
                         initComplete: function(settings, json) {
                          $('#loadingSpinner').hide();
                        },
                        "drawCallback": function ( settings ) {
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
             
                        api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                            if ( last !== group ) {
                                $(rows).eq( i ).before(
                                    '<tr class="group"><td colspan="7">'+group+'</td></tr>'
                                );
             
                                last = group;
                            }
                        } );
                    }

            });

      }

   };

   init();
   return publicInt;

}
