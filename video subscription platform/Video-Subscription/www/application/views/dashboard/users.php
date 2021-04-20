<div class="row">
	<div class="col-sm-12">
		<div class="card-box table-responsive">
			<a href="<?php echo site_url('dashboard/users/add/'); ?>" class="btn btn-sm btn-primary waves-effect waves-light pull-right"><?php echo $this->lang->line('newUser'); ?></a>
			<table id="datatable-colvid" class="table table-striped table-bordered">
				<thead>
					<tr>
                        <th class="text-right">#</th>
						<th class="text-center"><?php echo $this->lang->line('username'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('mail'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('role'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('status'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('registered'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('ip'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('options'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($getUsers)) echo $getUsers; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
