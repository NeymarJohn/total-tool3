<div class="row">
	<div class="col-sm-12">
		<div class="card-box table-responsive">
			<a href="<?php echo site_url('dashboard/posts/add/'); ?>" class="btn btn-sm btn-primary waves-effect waves-light pull-right"><?php echo $this->lang->line('New Post'); ?></a>
			<table id="datatable-colvid" class="table table-striped table-bordered">
				<thead>
					<tr>
                        <th class="text-right">#</th>
						<th class="text-center"><?php echo $this->lang->line('post'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('category'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('status'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('uploaded'); ?></th>
						<th class="text-center"><?php echo $this->lang->line('options'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($getPosts)) echo $getPosts; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
