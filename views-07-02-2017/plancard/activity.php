<?php 
$this->title = 'Activity Details';
if ($response[0]['status'] == 'submitted') {
	$this->params['breadcrumbs'][] = ['label' => 'History', 'url' => ['history']];
} else {
	$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
// echo '<pre>';
// print_r($response[0]);exit;
/*  echo '<pre>';
print_r($actvityLabels);exit; */
//labels display
$step_1_label_name = '';
$step_2_label_name = '';
$step_3_label_name_1 = '';
$step_3_label_name_2 = '';
$step_3_label_name_3 = '';
$step_3_label_name_4 = '';
$step_3_label_name_5 = '';
$step_4_label_name = '';
$step_5_label_name = '';
$step_2_channel_label_name[0] = '';
$step_2_channel_label_name[1] = '';
$step_2_channel_label_name[2] = '';
$step_3_label_name = '';
if($response[0]['activity'] != 'Channel Card') {
	if (!empty($actvityLabels)) {
		if (array_key_exists('step1', $actvityLabels)) {
			$step_1_label_name = $actvityLabels['step1'][0];
		} else {
			$step_1_label_name = '';
		}
		if (array_key_exists('step2', $actvityLabels)) {
			$step_2_label_name = $actvityLabels['step2'][0];
		} else {
			$step_2_label_name = '';
		}
		if (array_key_exists('step3', $actvityLabels)) {
			if ($response[0]['activity'] == 'Farm and Home Visit') {
				$step_3_label_name = $actvityLabels['step3'];
				if (array_key_exists(0, $step_3_label_name)) {
					$step_3_label_name_1 = $step_3_label_name[0];
				}
				if (array_key_exists(1, $step_3_label_name)) {
					$step_3_label_name_2 = $step_3_label_name[1];
				}
			} else if ($response[0]['activity'] == 'Farmer Group Meeting') {
				$step_3_label_name = $actvityLabels['step3'];
				if (array_key_exists(0, $step_3_label_name)) {
					$step_3_label_name_1 = $step_3_label_name[0];
				}
				if (array_key_exists(1, $step_3_label_name)) {
					$step_3_label_name_2 = $step_3_label_name[1];
				}
				if (array_key_exists(2, $step_3_label_name)) {
					$step_3_label_name_3 = $step_3_label_name[2];
				}
			} else if ($response[0]['activity'] == 'Mass Campaign') {
				$step_3_label_name = $actvityLabels['step3'];
				if (array_key_exists(0, $step_3_label_name)) {
					$step_3_label_name_1 = $step_3_label_name[0];
				}
				if (array_key_exists(1, $step_3_label_name)) {
					$step_3_label_name_2 = $step_3_label_name[1];
				}
				if (array_key_exists(2, $step_3_label_name)) {
					$step_3_label_name_3 = $step_3_label_name[2];
				}
				if (array_key_exists(3, $step_3_label_name)) {
					$step_3_label_name_4 = $step_3_label_name[3];
				}
			} else if ($response[0]['activity'] == 'Demonstration') {
				$step_3_label_name = $actvityLabels['step3'];
				if (array_key_exists(0, $step_3_label_name)) {
					$step_3_label_name_1 = $step_3_label_name[0];
				}
				if (array_key_exists(1, $step_3_label_name)) {
					$step_3_label_name_2 = $step_3_label_name[1];
				}
				if (array_key_exists(2, $step_3_label_name)) {
					$step_3_label_name_3 = $step_3_label_name[2];
				}
				if (array_key_exists(3, $step_3_label_name)) {
					$step_3_label_name_4 = $step_3_label_name[3];
				}
				if (array_key_exists(4, $step_3_label_name)) {
					$step_3_label_name_5 = $step_3_label_name[4];
				}
			}
		}
		if (array_key_exists('step4', $actvityLabels)) {
			$step_4_label_name = $actvityLabels['step4'][0];
		}
		if (array_key_exists('step5', $actvityLabels)) {
			$step_5_label_name = $actvityLabels['step5'][0];
		}
	}
} else {
	if (!empty($actvityLabels)) {
		if (array_key_exists('step2', $actvityLabels)) {
			$step_2_channel_label_name = $actvityLabels['step2'];
		}
		if (array_key_exists('step3', $actvityLabels)) {
			$step_3_label_name = $actvityLabels['step3'][0];
		}
	}
}
?>
<?php if (!empty($actvityLabels)) { ?>
<div class="panel-body panel-bg">
	<h3 class="panel-title">Details</h3>
	<div class="row mt15">
		<div class="col-md-4">
			<div class="white-box ">
				<form class="form-horizontal">
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Date
							:</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?= date('d/m/Y', strtotime($response[0]['planned_date'])); ?>
						</label>
					</div>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Activity
							:</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input">  <?php if($response[0]['activity'] != 'Channel Card') {
								echo ucfirst($response[0]['activity']);
							} else {
										echo 'Partner Visit';
									} ?>
						</label>
					</div>
					<?php if($response[0]['activity'] != 'Channel Card') { ?>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label"><?= (count($label_names_display) > 0 ? ucfirst($label_names_display['crop_label']) :'Crop') ?>
							Name:</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['crop_name']; ?>
						</label>
					</div>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label"><?= (count($label_names_display) > 0 ? ucfirst($label_names_display['product_label']) :'Product') ?>
							Name:</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['product_name']; ?>
						</label>
					</div>
					<?php } ?>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label"><?= (count($label_names_display) > 0 ? ucfirst($label_names_display['village_label']) :'Village') ?>:</label>
						<label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['village_name']; ?>
						</label>
					</div>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Plan
							Type :</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?= ucfirst($response[0]['plan_type']); ?>
						</label>
					</div>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Created
							by :</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['created']; ?>
						</label>
					</div>
					<div class="form-group row">
						<label class="col-xs-12 col-sm-3 col-md-6  col-lg-5 control-label">Assigned
							to :</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['assignee']; ?>
						</label>
					</div>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Status
							:</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=ucwords(str_replace('_',' ',$response[0]['status'])); ?>
						</label>
					</div>
					<?php if ($response[0]['status'] == 'submitted') { ?>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Submission
							on:</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?php echo date("d/m/Y", strtotime($response[0]['updated_date'])); ?>
						</label>
					</div>
					<?php } ?>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Activity
							Type :</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"> <?php if($response[0]['activity'] != 'Channel Card') {
								echo ucfirst($response[0]['card_type']);
							} else {
										echo 'Partner Visit';
									} ?>
						</label>
					</div>
					<?php if($response[0]['card_type'] == 'channel card') { ?>
					<div class="form-group row">
						<label
							class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label"><?= (count($label_names_display) > 0 ? ucfirst($label_names_display['partner_label']) :'Partner') ?>:
						</label> <label
							class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['channel_partner']; ?>
						</label>
					</div>
					<?php } ?>
				</form>
			</div>
		</div>
		<?php if(!empty($actvityLabels) && $response[0]['status'] == 'submitted') { ?>
		<div class="col-md-8">
			<div class="white-box mb15 form-panel">
				<?php if($response[0]['activity'] == 'Channel Card') { ?>
				<form class="form-horizontal">

					<?php if($response[0]['status'] == 'submitted' && array_key_exists('Products',$response[0]) && !empty($response[0]['Products']))
								{ ?>
					<div class="white-box mb15">
						<?php $products = $response[0]['Products'];
						$length = count($products);
						$k = 1;
											foreach ($products as $product) { ?>

						<h2 class="panel-title">
							<?= ucfirst($product['product_name'])?>
						</h2>
						<br />
						<?php if ($product['demand_volume_label'] != '') {?>
						<div class="row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($product['demand_volume_label'])?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?= ucfirst($product['demand_volume'])?>
							</label>
						</div>
						<?php } ?>
						<?php if ($product['liquidation_status_label'] != '') {?>
						<div class="row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($product['liquidation_status_label'])?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?= ucfirst($product['liquidation_status'])?>
							</label>
						</div>
						<?php } ?>
						<?php if ($product['season_progress_label'] != '') {?>
						<div class=" row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($product['season_progress_label'])?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?= ucfirst($product['season_progress'])?>
							</label>
						</div>
						<?php } ?>
						<?php if ($product['collection_value_four_label'] != '') {?>
						<div class=" row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($product['collection_value_four_label'])?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?= ucfirst($product['collection_value_four'])?>
							</label>
						</div>
						<?php } ?>
						<?php if ($product['collection_value_five_label'] != '') {?>
						<div class=" row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($product['collection_value_five_label'])?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?= ucfirst($product['collection_value_five'])?>
							</label>
						</div>
						<?php } ?>
						<?php if ($k != $length){ ?>
						<hr>
						<?php  } ?>

						<?php  $k++; 
}?>
					</div>
					<?php } ?>

				</form>
				<?php } ?>
				<form class="form-horizontal">
					<div class="white-box  mb15">
						<?php  if ($step_1_label_name != '') { ?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_1_label_name) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?= ucfirst($response[0]['sub_activity_name']); ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_2_label_name != '') { ?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_2_label_name)?>:</label>
							<label
								class="col-xs-12 col-sm-6  col-md-6  col-lg-9 control-input"><?php echo strip_tags($response[0]['purpose']); ?>
							</label>
						</div>
						<?php } ?>
						<?php if($response[0]['activity'] == 'Farm and Home Visit') { ?>
						<?php if ($step_3_label_name_1 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_1)?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?php echo strip_tags($response[0]['contacted_person_name']); ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_2 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_2)?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['contacted_person_phone']; ?>
							</label>
						</div>
						<?php } ?>
						<?php } ?>
						<?php if($response[0]['activity'] == 'Farmer Group Meeting') { ?>
						<?php if ($step_3_label_name_1 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_1) ?>:
							</label> <label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_farmers']; ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_2 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_2) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_female_farmers']; ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_3 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_3) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_retailers']; ?>
							</label>
						</div>
						<?php } 
}?>
						<?php if($response[0]['activity'] == 'Mass Campaign') { ?>
						<?php if ($step_3_label_name_1 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_1) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_farmers']; ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_2 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_2) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_female_farmers']; ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_3 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_3) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_retailers']; ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_4 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_4) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_villages']; ?>
							</label>
						</div>
						<?php } 
}?>
						<?php if($response[0]['activity'] == 'Demonstration') { ?>
						<?php if ($step_3_label_name_1 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_1)?>:
							</label> <label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?php echo strip_tags($response[0]['contacted_person_name']); ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_2 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_2)?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['contacted_person_phone']; ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_3 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_3) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_farmers']; ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_4 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_4) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_female_farmers']; ?>
							</label>
						</div>
						<?php } ?>
						<?php if ($step_3_label_name_5 != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_3_label_name_5) ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['no_of_dealers']; ?>
							</label>
						</div>
						<?php } 
} ?>
						<?php if($response[0]['activity'] == 'Channel Card') { ?>
						<?php if ($step_2_channel_label_name[0] != '') {?>
						<div class="form-group row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?=$step_2_channel_label_name[0]; ?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?=$response[0]['cfeedback']; ?>
							</label>
						</div>
						<?php } ?>
					
						<?php  } ?>
						<?php if ($response[0]['activity'] != 'Channel Card') { ?>
						<?php if ($step_4_label_name != '') { ?>
						<div class=" row">
							<label
								class="col-xs-12 col-sm-3  col-md-6  col-lg-3 control-label"><?= ucfirst($step_4_label_name)?>:</label>
							<label
								class="col-xs-12  col-sm-6  col-md-6  col-lg-9 control-input"><?php echo strip_tags($response[0]['feedback']);?>
							</label>

						</div>
						<?php } 
}?>
					</div>
					<?php if ($step_5_label_name != '' || $step_3_label_name != '') { ?>

					<?php if(!empty($response[0]['images']))
											{ ?>
					<div class="white-box mb15">
						<div class="row">
							<div class="form-group">
								<label class="col-sm-6 control-label"><?php if($response[0]['card_type'] == 'channel card') {
									echo ucfirst($step_3_label_name);
								} else {
									echo ucfirst($step_5_label_name);
								}
									?>
								</label>
							</div>
						</div>
						<?php } ?>
						<div class=" row">
						<?php foreach($response[0]['images'] as $image) {?>
						<div class="col-xs-12 col-sm-12  col-md-6  col-lg-6">
								<div class="activity-img-block mb15">
									<a class="thumbnail" href="#"> <img class="img-responsive"
										src="<?= Yii::getAlias('@imageurl').'/'.$image; ?>">
									</a>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
			
			</div>
			<?php   
}?>
			<?php 
}?>
			</form>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
</div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3 class="modal-title">
					<?php
					if($response[0]['card_type'] == 'channel card') {
							echo ucfirst($step_3_label_name);
						} else {
							echo ucfirst($step_5_label_name);
						} ?>
				</h3>
			</div>
			<div class="modal-body"></div>

		</div>
	</div>
</div>


<!-- Image Gallery Modal popup End  -->
<?php } else { ?>
<h3 class="panel-title">Details</h3>
<div class="row mt15">
									<div class="col-md-4">
								
									<div class="white-box ">
							<div class="form-horizontal" role="form">
							  <div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Date:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?= date('d/m/Y', strtotime($response[0]['planned_date'])); ?></label>
								</div>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Activity:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?php if($response[0]['activity'] != 'Channel Card') {
									echo ucfirst($response[0]['activity']);
									} else {
										echo 'Partner Visit';
									}  ?></label>
								</div>
								<?php if($response[0]['activity'] != 'Channel Card') { ?>
									<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Crop Name:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['crop_name']; ?></label>
								</div>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Product Name:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['product_name']; ?></label>
								</div>
								
								<?php }?>
		
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Village:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['village_name']; ?></label>
								</div>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Plan Type:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?= ucfirst($response[0]['plan_type']); ?></label>
								</div>
								</div>
								<div class="form-horizontal" role="form">
							
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Created by:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['created']; ?></label>
								</div>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Assigned to:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['assignee']; ?></label>
								</div>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Status:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=ucwords(str_replace('_',' ',$response[0]['status'])); ?></label>
								</div>
								<?php if ($response[0]['status'] == 'submitted') { ?>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Submission on:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?php echo date("d/m/Y", strtotime($response[0]['updated_date'])); ?></label>
								</div>	
								<?php }?>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Activity Type:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input">
								<?php if($response[0]['activity'] != 'Channel Card') {
									echo ucfirst($response[0]['card_type']);
									} else {
										echo 'Partner Visit';
									} ?></label>
								</div>
									
								</div>
								</div>
								
								
								
							
								</div>
								<?php if ($response[0]['status'] == 'submitted') { ?>
<!-- 								<div class="hr"></div> -->
<!-- 								<div>Collection</div> -->
								<div class="col-md-8">
								<div class="white-box">
								<div class="white-box mb15 clearfix">
								
							<div class="form-horizontal" role="form">
						
							<?php if($response[0]['activity'] == 'Farm and Home Visit' || $response[0]['activity']=='Demonstration')
								{?>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Farmer Name:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?php echo strip_tags($response[0]['contacted_person_name']); ?></label>
								</div>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Mobile No:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['contacted_person_phone']; ?></label>
								</div>
								<?php } 
								if($response[0]['activity'] !='Channel Card'  && $response[0]['activity'] !='Farm and Home Visit'){?>
								
							  <div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">No of Farmers:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['no_of_farmers']; ?></label>
								</div>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">No of Female Farmers:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['no_of_female_farmers']; ?></label>
								</div>
							
								<?php } if($response[0]['activity']=='Mass Campaign')
								{?>
								<div class="form-group">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">No of Villages:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['no_of_villages']; ?></label>
								</div>
									<div class="form-group">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">No of Retailers:</label>
								</div>
							  <?php }?>
							  <?php if($response[0]['activity']=='Demonstration')
								{?>
								<div class="form-group">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">No of Dealers:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['no_of_dealers']; ?></label>
								</div>
							  <?php }?>
							    <?php if($response[0]['activity']=='Farmer Group Meeting')
								{?>
								<div class="form-group">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">No of Retailers:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['no_of_retailers']; ?></label>
								</div>
							  <?php }?>
							 
							  <?php if($response[0]['activity']=='Channel Card')
								{?>
								<div class="form-group">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label"> Partner:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['channel_partner']; ?></label>
								</div>
								<div class="form-group">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label"> Status:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['actual_value']; ?></label>
								</div>
								<div class="form-group">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label"> Target:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?=$response[0]['target_value']; ?></label>
								</div>

								
								 <?php }?>
								</div>
								
								<div class="form-horizontal" role="form">
							  <?php if($response[0]['activity'] != 'Channel Card')
								{    if ($response[0]['activity'] == 'Demonstration') { ?>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Observation:</label>
								<div  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><p><?php echo strip_tags($response[0]['purpose']); ?></p></div>
								</div>
							<?php 	} else { ?>
							
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Sub Activity:</label>
								<label  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><?= ucfirst($response[0]['sub_activity_name']); ?></label>
								</div>	
								<?php }?>
								<div class="form-group row">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Purpose:</label>
								<div  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><p><?php echo strip_tags($response[0]['purpose']); ?></p></div>
								</div> 
								</div>
								<?php } ?>
								<div class="form-horizontal ">
								<label  class="col-xs-12 col-sm-3  col-md-6  col-lg-5 control-label">Feedback:</label>
								<div  class="col-xs-12  col-sm-6  col-md-6  col-lg-7 control-input"><p><?php 
								if($response[0]['activity']=='Channel Card'){
									echo strip_tags($response[0]['cfeedback']);
								} else {
									echo strip_tags($response[0]['feedback']);
								}
								
								
								 ?></p></div>
								</div>
								
								</div>
											
												<?php if($response[0]['activity'] !='Channel Card'){ ?>	
															
								
<?php if(!empty($response[0]['images'])) { ?>
<div class="white-box">
														
<div class="col-lg-12">
        <div class="row">
			<div class="form-group">
				<label  class="col-sm-6 control-label">Images:</label>
			</div>
        </div>
		<?php foreach ($response[0]['images'] as $image) { ?>
			<div class="col-lg-3 col-md-4 col-xs-12 thumb">
				<a class="thumbnail" href="#">
					<img class="img-responsive" src="<?= Yii::getAlias('@imageurl').'/'.$image; ?>" alt="">
				</a>
			</div>
		<?php } ?>
<?php }?>							
    
    <!-- Image Gallery Modal popup  --> 
    </div>
   <?php }?>
   <div class="clearfix"></div>
								</div>
							
								
							
								
								<?php  if($response[0]['activity'] == 'Channel Card')
										{ 
									if($response[0]['status'] == 'submitted' && array_key_exists('Products',$response[0]))
								{ ?>
									
								<div class="white-box">
								<div>
									<div class="table-responsive">
									
									<table class="table table-striped">
										<thead>
										  <tr>
											<th>Products</th>
											<th>Supplied</th>
											<th>Liquidated</th>
											<th>Season Progress %</th>
										  </tr>
										</thead>
										<tbody>
										<?php $products = $response[0]['Products'];
								foreach($products as $product)
								{ ?>
									<tr>
									<td><?= $product['product_name'];?></td>
									<td><?= $product['demand_volume'].' '.$product['product_unit'];?></td>
									<td><?= $product['liquidation_status'].' '.$product['product_unit'];?></td>
									<td><?= $product['season_progress'];?> %</td>
									</tr>
								<?php }?> 		
								<?php }}?>
										
										  
										
											</tbody>
									</table>
									
								</div>
															
								</div>
								</div>
								</div>
								</div>
					</div>
								
						<?php }?>	

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
  <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 class="modal-title">Image</h3>
	</div>
	<div class="modal-body">
		
	</div>
	
   </div>
  </div>
</div>
<!-- Image Gallery Modal popup End  -->
<?php } ?>

<?php 
$current_controller = Yii::$app->controller->id;
$plancard_status = $response[0]['status'];
$script = <<< JS
//for menu hilight
	if ("$current_controller" == "plancard" && "$plancard_status" != "submitted") {
		$('#planindex').addClass('active');
	} else if ("$current_controller" == "plancard" && "$plancard_status" == "submitted") {
		$('#planhistory').addClass('active');
	}
//end
	$('.thumbnail').click(function(){
	  	$('.modal-body').empty();
	  	var title = $(this).parent('a').attr("title");
	  	$('.modal-title').html(title);
	  	$($(this).parents('div').html()).appendTo('.modal-body');
	  	$('#myModal').modal({show:true});
	});

JS;
$this->registerJs($script);
?>