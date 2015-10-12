<div id="<?php echo self::$prefix_dash; ?>content">

	<h1><?php _e('Settings Page', self::$text_domain); ?></h1>

	<form method="post" class="form-horizontal" role="form">

		<!-- Add a BootStrap Panel for the settings -->

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php _e('General Section', self::$text_domain); ?></h3>
			</div>
			<div class="panel-body">

				<!-- Text -->

				<div class="form-group">
					<label for="<?php echo self::$prefix_dash; ?>text" class="col-sm-3 control-label"><?php _e('Text', self::$text_domain); ?></label>
					<div class="col-sm-9">
						<input id="<?php echo self::$prefix_dash; ?>text" name="<?php echo self::$prefix_dash; ?>text" class="form-control" type="text" value="<?php echo isset($settings['text']) ? esc_attr($settings['text']) : ''; ?>">
						<em class="help-block"><?php _e('This is a description.', self::$text_domain); ?></em>
					</div>
				</div>

				<!-- Text Area -->

				<div class="form-group">
					<label for="<?php echo self::$prefix_dash; ?>textarea" class="col-sm-3 control-label"><?php _e('Text Area', self::$text_domain); ?></label>
					<div class="col-sm-9">
						<textarea rows="10" cols="50" id="<?php echo self::$prefix_dash; ?>textarea" name="<?php echo self::$prefix_dash; ?>textarea" class="form-control"><?php echo isset($settings['textarea']) ? esc_attr($settings['textarea']) : ''; ?></textarea>
						<em class="help-block"><?php _e('This is a description.', self::$text_domain); ?></em>
					</div>
				</div>

				<!-- Checkbox -->

				<div class="form-group">
					<label for="<?php echo self::$prefix_dash; ?>checkbox" class="col-sm-3 control-label"><?php _e('Checkbox', self::$text_domain); ?></label>
					<div class="col-sm-9">
						<input type="checkbox" id="<?php echo self::$prefix_dash; ?>checkbox" name="<?php echo self::$prefix_dash; ?>checkbox" class="form-control" <?php echo isset($settings['checkbox']) && $settings['checkbox'] ? 'checked="checked"' : ''; ?>/>
						<em class="help-block"><?php _e('This is a description.', self::$text_domain); ?></em>
					</div>
				</div>

				<!-- Select -->

				<div class="form-group">
					<label for="<?php echo self::$prefix_dash; ?>select" class="col-sm-3 control-label"><?php _e('Select', self::$text_domain); ?></label>
					<div class="col-sm-9">
						<select id="<?php echo self::$prefix_dash; ?>select" name="<?php echo self::$prefix_dash; ?>select">
							<option value="small" <?php echo isset($settings['select']) && $settings['select'] == 'small' ? 'selected' : ''; ?>><?php _e('small', self::$text_domain); ?></option>
							<option value="medium" <?php echo isset($settings['select']) && $settings['select'] == 'medium' ? 'selected' : ''; ?>><?php _e('medium', self::$text_domain); ?></option>
							<option value="large" <?php echo isset($settings['select']) && $settings['select'] == 'large' ? 'selected' : ''; ?>><?php _e('large', self::$text_domain); ?></option>
						</select>
						<em class="help-block"><?php _e('This is a description.', self::$text_domain); ?></em>
					</div>
				</div>

				<!-- Radio -->

				<div class="form-group">
					<label for="<?php echo self::$prefix_dash; ?>radio" class="col-sm-3 control-label"><?php _e('Radio', self::$text_domain); ?></label>
					<div class="col-sm-9">

						<div class="radio">
							<label>
								<input type="radio" name="<?php echo self::$prefix_dash; ?>radio" class="form-control" value="start" <?php echo isset($settings['radio']) && $settings['radio'] == 'start' ? 'checked="checked"' : ''; ?>>
								<?php _e('start', self::$text_domain); ?>
							</label>
						</div>

						<div class="radio">
							<label>
								<input type="radio" name="<?php echo self::$prefix_dash; ?>radio" class="form-control" value="middle" <?php echo isset($settings['radio']) && $settings['radio'] == 'middle' ? 'checked="checked"' : ''; ?>>
								<?php _e('middle', self::$text_domain); ?>
							</label>
						</div>

						<div class="radio">
							<label>
								<input type="radio" name="<?php echo self::$prefix_dash; ?>radio" class="form-control" value="end" <?php echo isset($settings['radio']) && $settings['radio'] == 'end' ? 'checked="checked"' : ''; ?>>
								<?php _e('end', self::$text_domain); ?>
							</label>
						</div>

						<em class="help-block"><?php _e('This is a description.', self::$text_domain); ?></em>
					</div>
				</div>

			</div>
		</div>

		<!-- Add a BootStrap Panel for the settings -->

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php _e('Other Section', self::$text_domain); ?></h3>
			</div>
			<div class="panel-body">

				<!-- URL -->

				<div class="form-group">
					<label for="<?php echo self::$prefix_dash; ?>url" class="col-sm-3 control-label"><?php _e('URL', self::$text_domain); ?></label>
					<div class="col-sm-9">
						<input id="<?php echo self::$prefix_dash; ?>url" name="<?php echo self::$prefix_dash; ?>url" class="form-control" type="url" size="50" value="<?php echo isset($settings['url']) ? esc_url($settings['url']) : ''; ?>">
						<em class="help-block"><?php _e('This is a description.', self::$text_domain); ?></em>
					</div>
				</div>

			</div>
		</div>

		<!-- Data Manager -->

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php _e('Data Manager', self::$text_domain); ?></h3>
			</div>
			<div class="panel-body">

				<?php $data_manager_settings->display_all_settings($settings); ?>

			</div>
		</div>

		<!-- Display Conditions -->

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php _e('Display Conditions', self::$text_domain); ?></h3>
			</div>
			<div class="panel-body">

				<?php $display_conditions_settings->display_all_settings($settings['display_conditions']); ?>

			</div>
		</div>

		<!-- Newsletter -->

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php _e('Newsletter', self::$text_domain); ?></h3>
			</div>
			<div class="panel-body">

				<?php $newsletter_integration_settings->display_all_settings($settings['args']['newsletter']); ?>

			</div>
		</div>

		<?php wp_nonce_field(self::$prefix . 'admin_settings'); ?>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="btn btn-primary" value="<?php _e('Save', self::$text_domain); ?>">
		</p>

	</form>
</div>