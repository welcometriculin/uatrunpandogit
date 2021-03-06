<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Roles;
use kartik\select2\Select2;
use yii\web\View;
use yii\web\JsExpression;
$manager_role_id = Roles::MANAGER;
?>
<?php $this->title = 'Performance';
//$this->params['breadcrumbs'][] = ['label' => 'Plan Cards', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;?>

<div class="">
<div class="text-right orange-text">
<?php 
$current_time = strtotime(date('H:i:s'));
$morning_time = strtotime('06:00:00');
$afternoon_time = strtotime('12:00:00');
$evening_time = strtotime('18:00:00');
$night_time = strtotime('23:59:59');
if( ($current_time >= $morning_time) && ($current_time <= $afternoon_time) )
{
	echo "<div>Last Updated On : ".date('j F Y,')." 06:00 AM</div>";
} else if(($current_time >= $afternoon_time )&&  ($current_time <= $evening_time)) {
	echo "<div>Last Updated On : ".date('j F Y,')." 12:00 PM</div>";
} else if(($current_time >= $evening_time) && ($current_time <= $night_time)) {
	echo "<div>Last Updated On : ".date('j F Y,')." 06:00 PM</div>";
} else if($current_time <= $morning_time )  {
	echo "<div>Last Updated On : ".date('j F Y,')." 12:00 AM</div>";
}
?>
		</div>
	<div id="performance-content">
		<div class="col-md-12 panel-bg mt20 clearfix">
			<div class="col-sm-8 col-md-9 form-inline">
				<div class="form-group col-sm-6 row your_team">
				
					<?php
$format = <<< SCRIPT
function format(state) {
console.log(state);
    if (!state.id) return state.text; // optgroup
		var st = state.text
		var array = st.split(" - ");
		console.log(array);
  //  return '<img class="flag" src="' + src + '"/>' + state.text;
			if(array[0] == 'All') {
				 return '<span>'+array[0] +'</span>';
			} else {
				return '<span>'+array[0] +'</span> - <span class = "drop">'+array[1]+'</span>';
			}
}
SCRIPT;
					$escape = new JsExpression("function(m) { return m; }");
					$this->registerJs($format, View::POS_HEAD);
					$form = ActiveForm::begin([
					]);
// Usage with ActiveForm and model
				echo $form->field($model, 'employee')->widget(Select2::classname(), [
				    'data' => $data,
				'size' => Select2::MEDIUM,
				    'options' => ['class' => 's2-togall-button','prompt' => 'All'],
				    'pluginOptions' => [
				        'allowClear' => false,
						'tags' => false,
 		'templateResult' => new JsExpression('format'),
        'templateSelection' => new JsExpression('format'),
        'escapeMarkup' => $escape,
				//'maximumSelectionLength'=> 1
				    ],
				])->label('Your Team'); ?>

					<?php ActiveForm::end(); ?>
				</div>
				<!-- 			<div class="form-group pull-right mt10"> -->
				<!-- 				<label class="control-label">Kishore:</label> -->
				<!-- 				<div class="rating pull-right"> -->
				<!-- 					<span><i class="fa fa-star"></i> </span><span><i class="fa fa-star"></i> -->
				<!-- 					</span><span><i class="fa fa-star"></i> </span><span><i -->
				<!-- 						class="fa fa-star"></i> </span><span><i class="fa fa-star"></i> </span> -->
				<!-- 				</div> -->
				<!-- 			</div> -->
			</div>

			<div class="col-md-3 col-sm-4 col-xs-12 pull-right">
				<button class="btn btn-year" type="button" id="year">Year</button>
				<button class="btn" type="button" id="month">Month</button>
			</div>
		</div>


		<div class="row">
		<!-- PLAN SUMMARY -->
		<?php echo $this->render('_plansummary'); ?>
		<!-- PLAN SUMMARY End -->
		
		<!-- DISTANCE -->
		<?php echo $this->render('_distance'); ?>
		<!-- DISTANCE End-->
		
		</div>
		<!-- ACTIVITY ROW -->
		<?php echo $this->render('_activitytabs', ['label_names_display' => $label_names_display]); ?>
		<!-- ACTIVITY ROW END-->
		<!-- VILLAGE ROW -->
		<?php echo $this->render('_villageactivity', ['label_names_display' => $label_names_display]); ?>
		<!-- VILLAGE ROW END-->

		<!-- NO. OF FARMERS-->
		<?php // echo $this->render('_farmerscount'); ?>
		<!-- NO. OF FARMERS-->
		<div class="pull-right">
		<a href="#" id="viewmore"><button id="view-more" class="btn btn-primary">View More <span class="fa fa-angle-right" aria-hidden="true"></span></button></a>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/canvasjs.min.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/picker.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/charts.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/hightheme.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/highcharts.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/exporting.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/dashboard.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>

<?php 
$dd='';
$viewmoreurl = Url::home();
$url = Url::home();
$url = $url.'/dashboard/performance';
$VillageActivity = '';
$village_crop_summary = '';
$user_id = Yii::$app->user->identity->id;
$current_year = date("Y");//for graphs update
$current_month = date("m") - 1; //for graphs update
$script = <<< JS
jQuery(document).ready(function($){
	time = 'year';
	id = $user_id;
	ajaxRequest(id,time);
		//distance highchart update
	 		chart.xAxis.dateTimeLabelFormats= { month: '%b'};
            chart.series[0].update({
		            pointStart: Date.UTC($current_year, 0, 1),
		            pointInterval : null,
		            pointIntervalUnit : 'month'
            });
      	//totalCampaigns highchart update
	 		totalCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            totalCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
        //fgmCampaigns highchart update
	 		fgmCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            fgmCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
        //fhvCampaigns highchart update
	 		fhvCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            fhvCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
		//fhvCampaigns highchart update
	 		fhvCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            fhvCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
		//mcCampaigns highchart update
	 		mcCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            mcCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
		//demoCampaigns highchart update
	 		demoCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            demoCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
	//year request
	$('#year').on('click',function(){
		time = 'year';
		$("#year").addClass("btn-year");
		$("#month").removeClass("btn-year");
		ajaxRequest(id,time);
		//distance highchart update
	 		chart.xAxis.dateTimeLabelFormats= { month: '%b'};
            chart.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
        //totalCampaigns highchart update
	 		totalCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            totalCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
        //fgmCampaigns highchart update
	 		fgmCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            fgmCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
        //fhvCampaigns highchart update
	 		fhvCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            fhvCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
        //mcCampaigns highchart update
	 		mcCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            mcCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
		//demoCampaigns highchart update
	 		demoCampaigns.xAxis.dateTimeLabelFormats= { month: '%b'};
            demoCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, 0, 1),
			            pointInterval : null,
			            pointIntervalUnit : 'month'
            });
	});
	//month request
	$('#month').on('click',function(){
		time = 'month';
		$("#month").addClass("btn-year");
		$("#year").removeClass("btn-year");
		ajaxRequest(id,time);
		//distance highchart update
	 		chart.xAxis.dateTimeLabelFormats= { day: '%e of %b'};
            chart.series[0].update({
			            pointStart: Date.UTC($current_year, $current_month, 1),
			            pointInterval: 24 * 3600 * 1000, // one day
			            pointIntervalUnit : null
            });
       //totalCampaigns highchart update
	 		totalCampaigns.xAxis.dateTimeLabelFormats= { day: '%e of %b'};
            totalCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, $current_month, 1),
			            pointInterval: 24 * 3600 * 1000, // one day
			            pointIntervalUnit : null
            });
		//fgmCampaigns highchart update
	 		fgmCampaigns.xAxis.dateTimeLabelFormats= { day: '%e of %b'};
            fgmCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, $current_month, 1),
			            pointInterval: 24 * 3600 * 1000, // one day
			            pointIntervalUnit : null
            });
        //fhvCampaigns highchart update
	 		fhvCampaigns.xAxis.dateTimeLabelFormats= { day: '%e of %b'};
            fhvCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, $current_month, 1),
			            pointInterval: 24 * 3600 * 1000, // one day
			            pointIntervalUnit : null
            });
        //mcCampaigns highchart update
	 		mcCampaigns.xAxis.dateTimeLabelFormats= { day: '%e of %b'};
            mcCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, $current_month, 1),
			            pointInterval: 24 * 3600 * 1000, // one day
			            pointIntervalUnit : null
            });
		//demoCampaigns highchart update
	 		demoCampaigns.xAxis.dateTimeLabelFormats= { day: '%e of %b'};
            demoCampaigns.series[0].update({
			            pointStart: Date.UTC($current_year, $current_month, 1),
			            pointInterval: 24 * 3600 * 1000, // one day
			            pointIntervalUnit : null
            });
            chartsRender();
           
	});
	//on change request
	$('#users-employee').change(function(){
		id = $('#users-employee').val();
		if (id == '') {
			id = $user_id;
		}
		ajaxRequest(id,time);
		AllCrops.render();
		AllProducts.render();
		chartsRender();
	});
	//for view more option
	$('#view-more').click(function(){
		id = $('#users-employee').val();
		if (id == '') {
			id = $user_id;
		}
		$('#viewmore').attr("href", "$viewmoreurl/plancard/history/"+id);
	});
	//charts rendering
	function charts(village_activity,crop_activity,product_activity,distance, total_campaigns, fgm, fhv, demo, mc, fgm_products, fhv_products, mc_products, demo_products, bc, bnc, ac, anc, count, fgm_crops, fhv_crops, mc_crops, demo_crops, village_crop_summary, village_product_summary, no_of_retailers, no_of_farmers, no_of_female_farmers,percentage)
	{
	//village actvities
		if (village_activity == 0) {
			//console.log(village_activity);
			VillageActivity.options.title.text = "No Results Found";
		    VillageActivity.options.data = 0;
		    VillageActivity.options.height = 60;
			VillageActivity.render();
		} else {
			//console.log(village_activity);
			VillageActivity.options.title.text = "";
			VillageActivity.options.data = village_activity;
			noOfVillages = village_activity[0].dataPoints.length;
			calcHeight = (noOfVillages*30)+110;
			VillageActivity.options.height = (calcHeight > 32000) ? 32000 : calcHeight;
			VillageActivity.render();
		}
	// All Crops
		if (crop_activity == 0) {
			$('#allCrops').css('height',0);
			AllCrops.options.title.text = "No Results Found";
		    AllCrops.options.data = 0;
			AllCrops.render();
		} else {
			$('#allCrops').css('height',240);
			AllCrops.options.title.text = "";
			AllCrops.options.data = crop_activity;
// 			noOfCrops = crop_activity[0].dataPoints.length;
// 			calcHeight = (noOfCrops*35)+100;
// 			AllCrops.options.height = calcHeight;
			AllCrops.render();
		}
	//all Products
		if (product_activity == 0) {
			$('#allProducts').css('height',0);
			AllProducts.options.title.text = "No Results Found";
		    AllProducts.options.data = 0;
			AllProducts.render();
		} else {
			$('#allProducts').css('height',240);
			AllProducts.options.title.text = "";
			AllProducts.options.data = product_activity;
// 			noOfCrops = product_activity[0].dataPoints.length;
// 			calcHeight = (noOfCrops*35)+100;
// 			AllProducts.options.height = calcHeight;
			AllProducts.render();
		}
	//Distance
		if (distance == 0) {
			$('.dashboard .distance .graph').css('height', 'auto');
			//chart.setTitle({text: "No Results Found"});
			//chart.series[0].hide();
			$('#title_distance').css('display','none');
			$('#distance').hide();
			$('#distance_empty').show();
		} else {
			$('.dashboard .distance .graph').css('height', 400);
			//chart.setTitle({text: ""});
			//chart.series[0].show();
			//chart.redraw();
			$('#title_distance').css('display','block');
			$('#distance').show();
			$('#distance_empty').hide();
			chart.series[0].setData(distance);
		}
	//total campaigns
		if (total_campaigns == 0) {
			$('.tab-content .travelchart').css('min-height', 'auto');
			$('#totalCampaigns').hide();
			$('#tc_tot').css('display','none');
			$('#totalCampaigns_empty').show();
			$('#tc_activities').removeClass('campaign-panel');//for break line
		} else {
			$('.tab-content .travelchart').css('min-height', 180);
			$('#totalCampaigns').show();
			$('#tc_tot').css('display','block');
			$('#totalCampaigns_empty').hide();
			$('#tc_activities').addClass('campaign-panel');//for break line
			//totalCampaigns.setSize(310, 240, false);
			totalCampaigns.series[0].setData(total_campaigns);
		}
	//fgm campaigns
		if (fgm == 0) {
			$('#FgmTotalCampaigns').hide();
			$('#fgm_tot').css('display','none');
			$('#FgmTotalCampaigns_empty').show();
			$('#fgm_activities').removeClass('campaign-panel');//for break line
		} else {
			$('#FgmTotalCampaigns').show();
			$('#fgm_tot').css('display','block');
			$('#FgmTotalCampaigns_empty').hide();
			$('#fgm_activities').addClass('campaign-panel');//for break line
			//fgmCampaigns.setSize(300, 300, false);
			fgmCampaigns.series[0].setData(fgm);
		}
	//fhv campaigns
		if (fhv == 0) {
			$('#FhvTotalCampaigns').hide();
			$('#fhv_tot').css('display','none');
			$('#FhvTotalCampaigns_empty').show();
			$('#fhv_activities').removeClass('campaign-panel');//for break line
		} else {
			$('#FhvTotalCampaigns').show();
			$('#fhv_tot').css('display','block');
			$('#FhvTotalCampaigns_empty').hide();
			$('#fhv_activities').addClass('campaign-panel');//for break line
			//fhvCampaigns.setSize(300, 300, false);
			fhvCampaigns.series[0].setData(fhv);
		}
	//mc campaigns
		if (mc == 0) {
			$('#McTotalCampaigns').hide();
			$('#mc_tot').css('display','none');
			$('#McTotalCampaigns_empty').show();
			$('#mc_activities').removeClass('campaign-panel');//for break line
		} else {
			$('#McTotalCampaigns').show();
			$('#mc_tot').css('display','block');
			$('#McTotalCampaigns_empty').hide();
			$('#mc_activities').addClass('campaign-panel');//for break line
			//mcCampaigns.setSize(300, 300, false);
			mcCampaigns.series[0].setData(mc);
		}
	//demo campaigns
		if (demo == 0) {
			$('#DemoTotalCampaigns').hide();
			$('#demo_tot').css('display','none');
			$('#DemoTotalCampaigns_empty').show();
			$('#demo_activities').removeClass('campaign-panel');//for break line
		} else {
			$('#DemoTotalCampaigns').show();
			$('#demo_tot').css('display','block');
			$('#DemoTotalCampaigns_empty').hide();
			$('#demo_activities').addClass('campaign-panel');//for break line
			//demoCampaigns.setSize(300, 300, false);
			demoCampaigns.series[0].setData(demo);
		}
	//fgm products
		if (fgm_products == 0) {
			farmGroupProducts.options.title.text = "No Results Found";
		    farmGroupProducts.options.data[0].dataPoints = 0;
		    $('#farmGroupProducts').css('height',0);
			farmGroupProducts.render();
		} else {
			farmGroupProducts.options.title.text = "";
			farmGroupProducts.options.data[0].dataPoints = fgm_products;
			farmGroupProducts.render();
		}
	//fhv products
		if (fhv_products == 0) {
			farmHomeProducts.options.title.text = "No Results Found";
		    farmHomeProducts.options.data[0].dataPoints = 0;
		   $('#farmHomeProducts').css('height',0);
			farmHomeProducts.render();
		} else {
			farmHomeProducts.options.title.text = "";
			farmHomeProducts.options.data[0].dataPoints = fhv_products;
			farmHomeProducts.render();
		}
	//mc products
		if (mc_products == 0) {
			mcProducts.options.title.text = "No Results Found";
		    mcProducts.options.data[0].dataPoints = 0;
		    $('#mcProducts').css('height',0);
			mcProducts.render();
		} else {
			mcProducts.options.title.text = "";
			mcProducts.options.data[0].dataPoints = mc_products;
			mcProducts.render();
		}
	//demo products
		if (demo_products == 0) {
			DemoProducts.options.title.text = "No Results Found";
			$('#DemoProducts').css('height',0);
		    DemoProducts.options.data[0].dataPoints = 0;
			DemoProducts.render();
		} else {
			DemoProducts.options.title.text = "";
			DemoProducts.options.data[0].dataPoints = demo_products;
			DemoProducts.render();
		}
	//build completed
		if (bc == 0) {
			var div_width = '100%';
			$('#buildCompleted').css('width',div_width);
			BuildCompleted.options.title.text = "No Results Found";
		    BuildCompleted.options.data[0].dataPoints = 0;
			BuildCompleted.render();
		} else {
			//BuildCompleted.options.width = percentage[0];
			$('#buildCompleted').css('width',percentage[0]);
			BuildCompleted.options.title.text = "";
		    BuildCompleted.options.data[0].dataPoints = bc;
			BuildCompleted.render();
		}
	//build not completed
		if (bnc == 0) {
			var div_width = '100%';
			$('#buildNotCompleted').css('width',div_width);
			BuildNotCompleted.options.title.text = "No Results Found";
			BuildNotCompleted.options.data[0].dataPoints = 0;
			BuildNotCompleted.render();
		} else {
			//BuildNotCompleted.options.height = percentage[1];
			//alert( percentage[1]);
			$("#buildNotCompleted").css("width", percentage[1]);
			BuildNotCompleted.options.title.text = "";
			BuildNotCompleted.options.data[0].dataPoints = bnc;
			BuildNotCompleted.render();
		}
	//assigned  completed
		if (ac == 0) {
			var div_width = '100%';
			$('#assignedCompleted').css('width',div_width);
			AssignedCompleted.options.title.text = "No Results Found";
			AssignedCompleted.options.data[0].dataPoints = 0;
			AssignedCompleted.render();
		} else {
			//AssignedCompleted.options.width = percentage[3];
			$('#assignedCompleted').css('width',percentage[3]);
			AssignedCompleted.options.title.text = "";
		    AssignedCompleted.options.data[0].dataPoints = ac;
			AssignedCompleted.render();
		}
	//assigned  not completed
		if (anc == 0) {
			var div_width = '100%';
			$('#assignedNotCompleted').css('width',div_width);
			AssignedNotCompleted.options.title.text = "No Results Found";
		    AssignedNotCompleted.options.data[0].dataPoints = 0;
			AssignedNotCompleted.render();
		} else {
			//AssignedNotCompleted.options.width = percentage[2];
			$('#assignedNotCompleted').css('width',percentage[2]);
			AssignedNotCompleted.options.title.text = "";
		    AssignedNotCompleted.options.data[0].dataPoints = anc;
			AssignedNotCompleted.render();
		}
	//count
		if (count == 0) {
			$("#total_plans").html('');
		} else {
			$("#total_plans").html(count);
		}
	//fgm crops
		if (fgm_crops == 0) {
			$('#fgm_crops').hide();
 			$('#fgm_crops_empty').show();
		} else {
			$('#fgm_crops').html(fgm_crops).show();
			$('#fgm_crops_empty').hide();
		}
	//fhv crops
		if (fhv_crops == 0) {
			$('#fhv_crops').hide();
 			$('#fhv_crops_empty').show();
		} else {
			$('#fhv_crops').html(fhv_crops).show();
			$('#fhv_crops_empty').hide();
		}
	//mc crops
		if (mc_crops == 0) {
			$('#mc_crops').hide();
 			$('#mc_crops_empty').show();
		} else {
			$('#mc_crops').html(mc_crops).show();
			$('#mc_crops_empty').hide();
		}
	//demo crops
		if (demo_crops == 0) {
			$('#demo_crops').hide();
 			$('#demo_crops_empty').show();
		} else {
			$('#demo_crops').html(demo_crops).show();
			$('#demo_crops_empty').hide();
		}
	//village crop summary
		if (village_crop_summary == 0) {
			$('#village_crops').hide();
			$('#village_crops_empty').show();
		} else {
			$('#village_crops').html(village_crop_summary).show();
			$('#village_crops_empty').hide();
		}
	//village product summary
		if (village_product_summary == 0) {
			$('#village_products').hide();
			$('#village_products_empty').show();
		} else {
			$('#village_products').html(village_product_summary).show();
			$('#village_products_empty').hide();
		}
	//No. Of farmers
		$("#no_of_retailers").text(no_of_retailers);
		$("#no_of_farmers").text(no_of_farmers);
 		$("#no_of_female_farmers").text(no_of_female_farmers);
	}

	//ajax request
	function ajaxRequest(id,time)
	{
		 $.ajax({
		 	type: 'post',
		 	url:'$url',
			data:{id:id,time:time},
			beforeSend: function()
    		{
        		$('#loader-image').show();
    		},
			success: function(response){
// 			alert(response);
			// console.log(response);
			$('#loader-image').hide();
			if (response != 'not_exist') {
			   if(response.length > 0) {
				    res = eval(response);
					//console.log(response);
					//alert(res);
						//alert('va'+res[0]);
					if (res[0] == '' || res[0] == 0 || res[0] == null) {
						res[0] = 0;
					}
					village_activity = res[0];
			
					if (res[1] == '' || res[1] == 0 || res[1] == null) {
						res[1] = 0;
					}
				  	village_crop_summary = res[1];

	 				if (res[2] == '' || res[2] == 0 || res[2] == null) {
						res[2] = 0;
					}
					village_product_summary = res[2];
			
					if (res[3] == '' || res[3] == 0 || res[3] == null) {
						res[3] = 0;
					}
					crop_activity_summary = res[3];
					if (res[4] == '' || res[4] == 0 || res[4] == null) {
						res[4] = 0;
					}
					product_activity_summary = res[4];
					distance = res[5];
					
					if (distance == '' || distance == 0 || distance == null) {
						distance = 0;
						distance[0] = 0;
						distance[1] = 0;
						distance[2] = 0;
					distance_performance = 0;
					sum_distance = 	0;
					avg_distance = 	0;
					$("#total_distance").html(sum_distance);
					$("#avg_distance").html(avg_distance);
					} else {
					distance_performance = distance[0];
					sum_distance = 	distance[1];
					avg_distance = 	distance[2];
					$("#total_distance").html(sum_distance);
					$("#avg_distance").html(avg_distance);
					}
					total_campaigns_activities = res[6];
					if (total_campaigns_activities == '' || total_campaigns_activities == 0 || total_campaigns_activities == null) {
						total_campaigns = 0;
						tc_sum = 	0;
						tc_avg = 	0;
						$("#tc_total").html(tc_sum);
						$("#tc_avg").html(tc_avg);
					} else {
						total_campaigns = total_campaigns_activities[0];
						tc_sum = 	total_campaigns_activities[1];
						tc_avg = 	total_campaigns_activities[2];
						$("#tc_total").html(tc_sum);
						$("#tc_avg").html(tc_avg);
					}
			
					group_campaigns = res[7];
					//console.log(group_campaigns);
					if (group_campaigns == '' || group_campaigns == 0 || group_campaigns == null) {
						fgm = 0;
						fhv = 0;
						demo = 0;
						mc = 0;
						fgm_sum = 0;
						fgm_avg = 0;
						fhv_sum  = 0;
						fhv_avg  = 0;
						mc_sum  = 0;
						mc_avg  = 0;
						demo_sum  = 0;
						demo_avg  = 0;
						$("#fgm_total").html(fgm_sum);
						$("#fgm_avg").html(fgm_avg);
						$("#fhv_total").html(fhv_sum);
						$("#fhv_avg").html(fhv_avg);
						$("#mc_total").html(mc_sum);
						$("#mc_avg").html(mc_avg);
						$("#demo_total").html(demo_sum);
						$("#demo_avg").html(demo_avg);
					} else {
						fgm = group_campaigns[0]['fgm'];
						fhv = group_campaigns[0]['fhv'];
						demo = group_campaigns[0]['demo'];
						mc = group_campaigns[0]['mc'];
						fgm_sum = group_campaigns[1]['fgm'];
						fgm_avg = group_campaigns[2]['fgm'];
						fhv_sum = group_campaigns[1]['fhv'];
						fhv_avg = group_campaigns[2]['fhv'];
						mc_sum = group_campaigns[1]['mc'];
						mc_avg = group_campaigns[2]['mc'];
						demo_sum = group_campaigns[1]['demo'];
						demo_avg = group_campaigns[2]['demo'];
						$("#fgm_total").html(fgm_sum);
						$("#fgm_avg").html(fgm_avg);
						$("#fhv_total").html(fhv_sum);
						$("#fhv_avg").html(fhv_avg);
						$("#mc_total").html(mc_sum);
						$("#mc_avg").html(mc_avg);
						$("#demo_total").html(demo_sum);
						$("#demo_avg").html(demo_avg);
					}
					if (res[8] == '' || res[8] == 0 || res[8] == null) {
						res[8] = 0;
						activity_wise_crops['fgm'] = 0;
						activity_wise_crops['fhv'] = 0;
						activity_wise_crops['mc'] = 0;
						activity_wise_crops['demo'] = 0;
					}
					activity_wise_crops = res[8];
					fgm_crops = activity_wise_crops['fgm'];
					fhv_crops = activity_wise_crops['fhv'];
					mc_crops = activity_wise_crops['mc'];
					demo_crops = activity_wise_crops['demo'];

					if (res[9] == '' || res[9] == 0 || res[9] == null) {
						res[9] = 0;
						activity_wise_products['fgm'] = 0;
						activity_wise_products['fhv'] = 0;
						activity_wise_products['mc'] = 0;
	 					activity_wise_products['demo'] = 0;
					}
					activity_wise_products = res[9];
					fgm_products = activity_wise_products['fgm'];
					fhv_products = activity_wise_products['fhv'];
					mc_products = activity_wise_products['mc'];
	 				demo_products = activity_wise_products['demo'];
			
					if (village_crop_summary == '' || village_crop_summary == 0 || village_crop_summary == null) {
						village_crop_summary = 0;
					}
					village_crop_summary = village_crop_summary;

					if (village_product_summary == '' || village_product_summary == 0 || village_product_summary == null) {
						village_product_summary = 0;
					}
					village_product_summary = village_product_summary;

	// 				$('#village_crops').html(village_crop_summary).show();
	// 				$('#village_crops_empty').hide();
	// 				$('#village_products').html(village_product_summary).show();
	// 				$('#village_products_empty').hide();
	// 				$('#fgm_crops').html(fgm_crops).show();
	// 				$('#fgm_crops_empty').hide();
	// 				$('#fhv_crops').html(fhv_crops).show();
	// 				$('#fhv_crops_empty').hide();
	// 				$('#mc_crops').html(mc_crops).show();
	// 				$('#mc_crops_empty').hide();
	// 				$('#demo_crops').html(demo_crops).show();
	// 				$('#demo_crops_empty').hide();

					if (res[10] == '' || res[10] == 0 || res[10] == null) {
						res[10] = 0;
						plan_summary['bc'] = 0;
						plan_summary['bnc'] = 0;
						plan_summary['ac'] = 0;
						plan_summary['anc'] = 0;
						plan_summary['count'] = 0;
					}
					plan_summary = res[10];
					bc = plan_summary['bc'];
					//console.log(bc);
					bnc = plan_summary['bnc'];
					ac = plan_summary['ac'];
					anc = plan_summary['anc'];
					count = plan_summary['count'];
					percentage = plan_summary['chart_per'];
	// 				$("#total_plans").html(count);
						//console.log(res[11]);
					farmers_summary = res[11];
					if (farmers_summary == '' || farmers_summary == 0 || farmers_summary == null) {
						farmers_summary = 0;
						no_of_retailers = 0;
						no_of_farmers = 0;
						no_of_female_farmers = 0;
					} else {
						no_of_farmers = farmers_summary[0];
						no_of_female_farmers = farmers_summary[1];
						no_of_retailers = farmers_summary[2];
					}
	// 				$("#no_of_retailers").text(farmers_summary['no_of_retailers']);
	// 				$("#no_of_farmers").text(farmers_summary['no_of_farmers']);
	// 				$("#no_of_female_farmers").text(farmers_summary['no_of_female_farmers']);
															
					charts(village_activity,crop_activity_summary,product_activity_summary,distance_performance, total_campaigns, fgm, fhv, demo, mc, fgm_products, fhv_products, mc_products, demo_products, bc, bnc, ac, anc, count, fgm_crops, fhv_crops, mc_crops, demo_crops, village_crop_summary, village_product_summary, no_of_retailers, no_of_farmers, no_of_female_farmers,percentage);
					}
			    } else {
	// 				$('#village_crops').hide();
	// 				$('#village_crops_empty').show();
	// 				$('#village_products').hide();
	// 				$('#village_products_empty').show();
	// 				$('#fgm_crops').hide();
	// 				$('#fgm_crops_empty').show();
	// 				$('#fhv_crops').hide();
	// 				$('#fhv_crops_empty').show();
	// 				$('#mc_crops').hide();
	// 				$('#mc_crops_empty').show();
	// 				$('#demo_crops').hide();
	// 				$('#demo_crops_empty').show();
					charts(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
				}
			},
		});
	}


	$(".tab-panel-section .graph").mCustomScrollbar({
					/*setHeight:340,*/
					theme:"dark-3"
				});	
		    		function chartsRender() {
		    		 $('#summary_tab').on("shown.bs.tab",function(){
	AllCrops.render();
	AllProducts.render();
    $('#summary_tab').off(); // to remove the binded event after the initial rendering
});

$('#fgm_window').on("shown.bs.tab",function(){
	farmGroupProducts.render();
    $('#fgm_window').off(); // to remove the binded event after the initial rendering
});
$('#fhv_window').on("shown.bs.tab",function(){
	farmHomeProducts.render();
    $('#fhv_window').off(); // to remove the binded event after the initial rendering
});
$('#mc_window').on("shown.bs.tab",function(){
	mcProducts.render();
    $('#mc_window').off(); // to remove the binded event after the initial rendering
});
$('#demo_window').on("shown.bs.tab",function(){
	DemoProducts.render();
    $('#demo_window').off(); // to remove the binded event after the initial rendering
});
$('#main-menu-min').click(function(){
	$('#distance').highcharts().reflow();
	$('#FgmTotalCampaigns').highcharts().reflow();
	$('#DemoTotalCampaigns').highcharts().reflow();
	$('#FhvTotalCampaigns').highcharts().reflow();
	$('#McTotalCampaigns').highcharts().reflow();
	$('#totalCampaigns').highcharts().reflow();
	farmGroupProducts.render();
	farmHomeProducts.render();
	DemoProducts.render();
	mcProducts.render();
	AllCrops.render();
	AllProducts.render();
	VillageActivity.render();
})

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	$('#FgmTotalCampaigns').highcharts().reflow();
	$('#DemoTotalCampaigns').highcharts().reflow();
	$('#FhvTotalCampaigns').highcharts().reflow();
	$('#McTotalCampaigns').highcharts().reflow();
	$('#totalCampaigns').highcharts().reflow();
});	
		    		}
		    		
	function fontSize() {
	if ($(window).width() >= 768) {
		 VillageActivity.options.axisX.labelFontSize =14;
		 VillageActivity.options.axisY.labelFontSize =11;
		 AllCrops.options.axisX.labelFontSize =12;
		 AllCrops.options.axisY.labelFontSize =11;
		 AllProducts.options.axisY.labelFontSize =11;
		 AllProducts.options.axisX.labelFontSize =12;   		  						
		} else {
		 VillageActivity.options.axisX.labelFontSize =11;
		 VillageActivity.options.axisY.labelFontSize =11;
		 AllCrops.options.axisX.labelFontSize =12;
		 AllCrops.options.axisY.labelFontSize =11;
		 AllProducts.options.axisY.labelFontSize =11;
		 AllProducts.options.axisX.labelFontSize =12;    			  		
		}
}
fontSize();
$(window).resize(function(){
fontSize();
});
		  						
});

JS;
$this->registerJs($script);
?>
