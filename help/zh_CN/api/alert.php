	<h3>结果代码</h3>

	<div class="panel panel-default">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="nowrap">代码</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($_arr_alert as $_key=>$_value) { ?>
						<tr>
							<td class="nowrap"><?php echo $_key; ?></td>
							<td><?php echo $_value; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
