<div class="row">
	<div class="col-sm-12">
		<?php if(isset($msg)) echo $msg; ?>
		<div class="card-box table-responsive">
			<table id="datatable-buttons" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-right">#</th>
						<th class="text-center"><?php echo $this->lang->line('Email'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('Role'); ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Status'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('IP'); ?></th>
                        <th class="text-center"><?php echo $this->lang->line('Date created'); ?></th>
                        <th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($getNewsletters)) echo $getNewsletters; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
