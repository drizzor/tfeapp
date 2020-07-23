<?php render('admin-header', ['active' => 'planning',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	   
<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body mb-3">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Selection du chantier</h1>							    
			</div>
			<?= flash('success'); ?>
			<?= flash('fail'); ?>
			<form class="form" action="" method="post">
				<div class="row">
					<div class="form-group col-lg-5">
						<select class="form-control form-control-lg" id="construction" name="construction">
							<?php foreach($data['constructions'] as $construction): ?>
							<?php if($construction['constructions_id'] == $data['constructions_id']): ?>
							<option selected="selected" value="<?= $construction['constructions_id'] ?>"><?= $construction['constructions_name'] ?></option>
							<?php else: ?>
							<option value="<?= $construction['constructions_id'] ?>"><?= $construction['constructions_name'] ?></option>
							<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-action col-lg-1">
						<button type="submit" name="planning_search" class="btn btn-success btn-lg"><span class="fa fa-search"></span></button>
					</div>
				</div>
			</form>
		</div>
	</main>
</div>	       

<?php require APPROOT . '/views/inc/admin-footer.php'; ?>