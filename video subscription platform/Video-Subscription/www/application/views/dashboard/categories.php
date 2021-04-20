<div class="row">
	<div class="col-sm-12">
		<?php if(isset($msg)) echo $msg; ?>
		<div class="card-box table-responsive">
			<a href="<?php echo current_url().'/add/'; ?>" class="btn btn-sm btn-primary waves-effect waves-light pull-right"><?php echo $this->lang->line('newCategory'); ?></a>
			<table id="datatable-colvid" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-right">#</th>
						<th class="text-center"><?php echo $this->lang->line('category'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('url'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('subCategory'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('options'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($getCategories)) echo $getCategories; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
