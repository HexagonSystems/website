<header class="page-header relative" id="taskHeader">
	<h3>
		<span id="taskTitleLocation"><?php echo $data['task']->getTitle(); ?>
		</span> <small id="taskStatusLocation"><?php echo $data['task']->getStatus(); ?>
		</small>
	</h3>
	<article>
		Members
		<ul>
			<?php
			if(count($data['task']->getMembers()) == 0)
			{
				echo "<li>Add hours to Task to be shown here</li>";
			}else
			{
				foreach($data['task']->getMembers() as $member) {
					echo "<li>$member</li>";
				}
			}?>
		</ul>
	</article>
	<article id="taskDescriptionLocation">
		<?php echo $data['task']->getContent(); ?>
	</article>
</header>

<!-- Button trigger modal -->
<div class="panel panel-default hidden-print">
	<div class="panel-heading">Task Controls</div>
	<div role="form" class="form-inline panel-body">
		<div class="form-group inline col-xs-12 col-sm-4 col-lg-3">
			<a data-toggle="modal" href="#modal_comment"
				class="btn btn-primary btn-sm form-control">Add Update</a>
		</div>
		<div class="form-group inline col-xs-12 col-sm-4 col-lg-3">
			<a data-toggle="modal" href="#modal_hours"
				class="btn btn-primary btn-sm form-control">Add Hours</a>
		</div>
		<div class="form-group inline col-xs-12 col-sm-4 col-lg-3">
			<a data-toggle="modal" href="#modal_editTask"
				class="btn btn-primary btn-sm form-control">Edit Task</a>
		</div>
	</div>
</div>
<?php include_once 'modal_comment.php'; ?>
<?php include_once 'modal_hours.php'; ?>
<?php include_once 'modal_editTask.php'; ?>
<?php include_once 'modal_pickSearchMethod.php'; ?>

<table id="testtable"
	class="table table-rowBorder table-hover table-zebra table-responsive-dropLast2Col">

	<thead>
		<th class="table-colSmall">Tag</th>
		<th class="table-colLarge">Update</th>
		<th class="table-colMedium">Posted By</th>
		<th class="table-colMedium">Posted on</th>
	</thead>

	<tbody id="commentsContainer" class="tbodyFirstLineAccordion">
		<!-- Comments will be loaded here through AJAX -->
	</tbody>
</table>

<div class="text-center">
	<ul class="pagination" id="taskCommentPaginator">
		<?php 
		if($data['task']->getCommentCount() != null)
		{
			$amountOfPages = ceil($data['task']->getCommentCount() / 5);
			include 'paginator_generator.php';
		}else
		{
			echo "There was trouble loading the paginator";
		}
		?>
	</ul>
</div>

<div
	id="googlePieChart" style="width: 900px; height: 500px;"></div>

<script
	type="text/javascript" src="https://www.google.com/jsapi"></script>
<script
	type="text/javascript"
	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script
	src="<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/includes/js/TaskCommentsLoaderNEW.js"></script>
<script
	src="<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/includes/js/TableLoaderNEW.js"></script>
<script
	src="<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/includes/js/TaskLoaderNEW.js"></script>
<script>
ajaxBase = "<?php echo SITE_ROOT.AppBaseSTRIPPED; ?>Package/Task/";


mainTaskCommentsTable = {
		'print_location'	:	'#commentsContainer',
		'paginatorLocation'	:	"#taskCommentPaginator",
		'quantity_per_page'	:	5,
		'last_page'			:	-1,
		'memberId'			:	<?php echo unserialize($_SESSION['accountObject'])->getMemberId(); ?>,
		'memberFirstName'	: 	"<?php echo unserialize($_SESSION['accountObject'])->getFirstName(); ?>",
		'taskId'			:	<?php echo $data['task']->getId(); ?>,
		'content'			:	new Array()
};

/**
 * Create comment button
 * 
 * NEEDS TO BE REMOVED
 */
$(function() {
	$("#createCommentButton").click(
			function() {
				createComment(mainTaskCommentsTable, $("#inputTaskTag").val(), $("#inputTaskTitle").val(), $("#inputTaskContent").val());
			});
});

/**
 * Add hours button
 * 
 */
$(function() {
	$("#addHoursButton").click(
			function() {
				// run script to add hours through ajax
				addHours(mainTaskCommentsTable, $("#addHoursDatePicker").val(), $(
						"#addHoursHours").val(), $("#addHoursComment").val());
			});
});

/**
 * Comment section paginator on click event
 */
 $(document).on('click', ".pagination li a", function () {
		event.preventDefault();
		if($(this).text() == "<<")
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).parent().next().children().addClass("paginator-selected");
			loadComments(mainTaskCommentsTable, 1);
		}else if($(this).text() == ">>")
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).parent().prev().children().addClass("paginator-selected");
			loadComments(mainTaskCommentsTable, parseInt($(this).parent().prev().find(">:first-child").text()));
		}else
		{
			$(this).parent().siblings().children().removeClass("paginator-selected");
			$(this).addClass("paginator-selected");
			loadComments(mainTaskCommentsTable, parseInt($(this).text()));
		}
		
});



/**
 * Edit Task functions
 */
 editTaskArray = {
			'titleLocation'		: 	"#taskTitleLocation",
			'contentLocation'	:	"#taskDescriptionLocation",
			'statusLocation'	:	"#taskStatusLocation",
			'memberId'			:	<?php echo unserialize($_SESSION['accountObject'])->getMemberId(); ?>,
			'memberFirstName'	: 	"<?php echo unserialize($_SESSION['accountObject'])->getFirstName(); ?>",
			'taskId'			:	<?php echo $data['task']->getId(); ?>,
	};
 $(function() {
		$("#editTaskSubmitButton").click(function() {
			$("#editTaskForm").submit();
		});
	});
$(function() {
	$("#editTaskForm").submit(
			function(event) {
				editTask(editTaskArray, mainTaskCommentsTable['taskId'], $("#modal_taskTitle").val(), $("#modal_taskDscr")
						.val(), $("#modal_taskStatus option:selected").text());
				event.preventDefault();
			});
});

function loadGoogleChartsPieHours(tableConfig) {
	$.ajax({
       type: "POST",
       dataType: "json",
        data: {request : "userContribution", memberId : tableConfig['memberId'], taskId : tableConfig['taskId']},
		url: ajaxBase + "Model/TaskAJAX.php",
        success: function(resultData){
            google.setOnLoadCallback(drawChart(resultData));                                                   
        }
    }); 
    
    function drawChart(jsonData) {
    	// Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('googlePieChart'));
      chart.draw(data, {width: 400, height: 240});
    }

}

function load_page_data(tableConfig){
console.log("loadingPageData");

    $.ajax({
    	type: "POST",
        url: ajaxBase + "Model/TaskHoursAJAX.php",
        data: {request : "hoursContribution", memberId : tableConfig['memberId'], taskId : tableConfig['taskId']},
        async: false,
        success: function(data){
        console.log(data);
            if(data){
                chart_data = $.parseJSON(data);
                var dataArray = new Array();
               $.each(chart_data['data'], function(member) {
	               var tempArray = new Array();
	               tempArray.push(member);
	               tempArray.push(parseInt(chart_data['data'][member]));
	               dataArray.push(tempArray);
               });
                drawChart(dataArray, "Member Contribution", "Hours");
            }else
            {
            	console.log("error " + data);
            }
        },
    });
}

function drawChart(chart_data, chart1_main_title, chart1_vaxis_title) {
console.log(chart_data);


   var chart1_data = new google.visualization.DataTable(chart_data);
   chart1_data.addColumn('string', 'Member');
    chart1_data.addColumn('number', 'Hours');
    chart1_data.addRows(chart_data);
    var chart1_options = {
        title: chart1_main_title,
        backgroundColor: 'transparent',
        legend: true
    };

    var chart1_chart = new google.visualization.PieChart(document.getElementById('googlePieChart'));
    chart1_chart.draw(chart1_data, chart1_options);
}

google.load("visualization", "1", {packages:["corechart"]});

/**
 * Page on load
 */
$(document).ready(function() {
	loadComments(mainTaskCommentsTable, 1);

	$("#addHoursDatePicker").datepicker();
	$("#addHoursDatePicker").datepicker('setDate', new Date());
    $( "#addHoursDatePicker" ).datepicker( "option", "dateFormat", "dd-M-yy" );
    
    
	google.setOnLoadCallback(load_page_data(mainTaskCommentsTable));
    
    
});
</script>
