<div class="row">
	<div class="col-sm-12">
		<div class="card-box table-responsive">
			<a href="<?php echo site_url('dashboard/pages/add/'); ?>" class="btn btn-sm btn-primary waves-effect waves-light pull-right"><?php echo $this->lang->line('newPage'); ?></a>
			<table id="datatable-colvid" class="table table-striped table-bordered">
				<thead>
					<tr>
                        <th class="text-right">#</th>
						<th class="text-center"><?php echo $this->lang->line('title'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('URL'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('Sub Page'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('dateCreation'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('dateModified'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('options'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($getPages)) echo $getPages; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
