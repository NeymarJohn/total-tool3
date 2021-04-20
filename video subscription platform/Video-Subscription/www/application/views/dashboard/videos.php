<div class="row">
	<div class="col-sm-12">
		<a href="<?php echo site_url('dashboard/videos/episodes/'); ?>" class="btn btn-default btn-action waves-effect waves-light"><?php echo $this->lang->line('All episodes'); ?></a>
		<div class="card-box table-responsive">
			<a href="<?php echo site_url('dashboard/videos/addepisode/'); ?>" class="btn btn-sm btn-primary waves-effect waves-light pull-right"><?php echo $this->lang->line('New episode'); ?></a>
			<a href="<?php echo site_url('dashboard/videos/add/'); ?>" class="btn btn-sm btn-primary waves-effect waves-light pull-right m-r-10"><?php echo $this->lang->line('newVideo'); ?></a>
			<table id="datatable-colvid" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th class="text-right">#</th>
						<th class="text-center"><?php echo $this->lang->line('Video'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('Category'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('played'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('Status'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('videoSource'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('uploaded'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('options'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($getVideos)) echo $getVideos; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
