<?php 
$events = $this->get_events();
$priceQualifierClass = $this->add_namespace(array('price', 'qualifier'), '-');
$courseCodeClass = $this->add_namespace(array('course', 'code'), '-');
$courseNameClass = $this->add_namespace(array('course', 'name'), '-');
$courseLocationClass = $this->add_namespace(array('course', 'location'), '-');
$courseLinkClass = $this->add_namespace(array('course', 'link'), '-');
$soldOutClass = $this->add_namespace(array('sold', 'out'), '-');
$registerLinkClass = $this->add_namespace(array('register', 'link'), '-');
$placesLeftClass = $this->add_namespace(array('places', 'left'), '-');
$availableClass = $this->add_namespace(array('available'), '-');
$taxLabel = $this->plugin->get_option('tax_label', 'pricing');
$pricingBasis = $this->plugin->get_option('basis', 'pricing');
$defaultCurrency = $this->plugin->get_option('currency', 'pricing');
$showCurrencyIndicator = $this->plugin->get_option('show_currency_indicator', 'pricing');
$currencyIndicator = $this->plugin->get_option('currency_indicator', 'pricing');
$pricesIncludeTaxes = $this->plugin->to_boolean($this->plugin->get_option('inc_taxes', 'pricing'));
$groupSize = intval($this->plugin->get_option('group_size', 'event'));
$translateNumPlaces = $this->plugin->to_boolean($this->plugin->get_option('translate_places_to_status', 'event'));
?>

<div id="<?= $this->add_namespace('', '-'); ?>" class="<?= $this->plugin->add_namespace('widget', '-'); ?> <?= $this->plugin->add_namespace(array('sort', 'filter', 'table'), '-'); ?>">
	<?php if($errors) { ?>
		<div class="administrate-errors">
			<?= $errors; ?>
		</div>
	<?php } ?>
	<form method="post" action="">
		<p>
			
			<label>
				<?php _e('Show', 'administrate'); ?>
				<?php if ($show_categories) { ?>
					<select name="<?= $this->add_namespace('category'); ?>" id="<?= $this->add_namespace('category', '-'); ?>">
						<option value=''><?php _e('all', 'administrate'); ?></option>
						<?php foreach ($this->plugin->make_api_call('get_categories', $this->plugin->to_boolean($this->plugin->get_option('show_empty_courses', 'course'))) as $category) { ?>
							<?php $categoryId = $category->get_id() . ':0'; ?>
							<option
								value="<?= $categoryId; ?>"
								<?php if ($categoryId == $savedCategory) { ?>
									selected="selected"
								<?php } ?>
							><?= $category->get_name(); ?></option>
							<?php foreach ($category->get_subcategories() as $subcategory) { ?>
								<?php $subcategoryId = $category->get_id() . ':' . $subcategory->get_id(); ?>
								<option
									value="<?= $subcategoryId; ?>"
									<?php if ($subcategoryId == $savedCategory) { ?>
										selected="selected"
									<?php } ?>
								> - <?= $subcategory->get_name(); ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				<?php } ?>
				<?php _e('events', 'administrate'); ?>
			</label> 
			<label>
				<?php _e('in the next', 'administrate'); ?>
				<select name="<?= $this->add_namespace('month'); ?>" id="<?= $this->add_namespace('month', '-'); ?>">
					<?php for ($i = 1; $i <= 12; ++$i) { ?>
						<option
							value="<?= $i; ?>"
							<?php if ($i == $savedMonths) { ?>
								selected="selected"
							<?php } ?>
						><?= $i; ?></option>
					<?php } ?>
				</select>
				<?php _e('months', 'administrate'); ?>
			</label>	
			<label>
				<?php _e('in', 'administrate'); ?>
				<select name="<?= $this->add_namespace('location'); ?>" id="<?= $this->add_namespace('location', '-'); ?>">
					<option value=""><?php _e('all locations', 'administrate'); ?></option>
					<?php foreach ($this->plugin->make_api_call('get_locations_from_events', $events) as $location) { ?>
						<?php if (is_string($location)) { ?>
							<option
								value="<?= $location; ?>"
								<?php if ($location == $savedLocation) { ?>
									selected="selected"
								<?php } ?>
							><?= $location; ?></option>
						<?php } else { ?>
							<option
								value="<?= $location->get_name(); ?>"
								<?php if ($location->get_name() == $savedLocation) { ?>
									selected="selected"
								<?php } ?>
							><?= $location->get_name(); ?><?php _e(',', 'administrate'); ?> <?= $location->get_country(); ?></option>
						<?php } ?>
					<?php } ?>
				</select>
				<?php _e('.', 'administrate'); ?>
			</label>
			<input type="submit" value="<?php _e('go', 'administrate'); ?>" class="<?= $this->plugin->add_namespace('btn', '-'); ?>">
		</p>
	</form>
	
	<table cellspacing="0"<?php if ($show_prices) { ?> class="<?= $this->add_namespace(array('pricing', 'table'), '-'); ?>"<?php } ?><?php if (!empty($groupSize) && ($groupSize > 0)) { ?> data-group-size="<?= $groupSize; ?>"<?php } ?>>
		<thead>
			<tr>
				<?php if ($show_codes) { ?>
					<th scope="col" class="<?= $courseCodeClass; ?>"><?php _e('Code', 'administrate'); ?></th>
				<?php } ?>
				<?php if ($show_names) { ?>
					<th scope="col" class="<?= $courseNameClass; ?>"><?php _e('Course', 'administrate'); ?></th>
				<?php } ?>
				<th scope="col" class="<?= $courseLocationClass; ?>"><?php _e('Location', 'administrate'); ?></th>
				<th scope="col" data-sorter="startDate"><?php _e('Dates', 'administrate'); ?></th>
				<?php if ($show_times) { ?>
					<th scope="col" data-sorter="false"><?php _e('Time', 'administrate'); ?></th>
				<?php } ?>
				<?php if ($show_prices) { ?>
					<th scope="col" data-sorter="false">
						<?php _e('Price per Person', 'administrate'); ?>
						<span class="<?= $priceQualifierClass; ?>">
							<?php if (count($events) > 0) { ?>
								<?php $this->plugin->display_currency_selector($events); ?>
							<?php } ?>
							<?php if (!empty($taxLabel)) { ?>
								<?php if ($pricesIncludeTaxes) { ?>
									<abbr title="<?php _e('including', 'administrate'); ?>"><?php _e('inc.', 'administrate'); ?></abbr>
								<?php } else { ?>
									<abbr title="<?php _e('excluding', 'administrate'); ?>"><?php _e('exc.', 'administrate'); ?></abbr>
								<?php } ?>
								<?= $taxLabel; ?>
							<?php } ?>
						</span>						
					</th>
				<?php } ?>
				<th scope="col" data-sorter="false"><?php _e('Status', 'administrate'); ?></th>
			</tr>
		</thead>
		<tbody>
			
			<?php foreach ($events as $event) { ?>
				
				<?php if (($show_sold_out || !$event->is_sold_out($translateNumPlaces)) && ($show_today || !$event->is_today())) { ?>
				
					<?php 
					//  Set the number of months until the event
					$dates = $event->get_dates();
					$times = $event->get_times();
					$numMonths = $this->plugin->get_months_until($dates['start']);
					
					//  Get a flat list of categories
					$cats = array();
					$subcats = array();
					foreach ($event->get_categories() as $category) {
						array_push($cats, $category->get_id());
						foreach ($category->get_subcategories() as $subcategory) {
							array_push($subcats, $subcategory->get_id());	
						}
					}
					$categoriesList = join(',', $cats);
					$subcategoriesList = join(',', $subcats);
					
					//  Determine whether this event should be shown at all
					$inMonthRange = ($numMonths <= $num_months);
					
					//  Set the course URL
					$courseUrl = $this->plugin->get_course_url($event->get_course());
					?>
					<?php if ($inMonthRange && !$this->plugin->filter_object_is_hidden('course', $event->get_course_code())) { ?>
						<tr data-categories="<?= $categoriesList; ?>" data-subcategories="<?= $subcategoriesList; ?>" data-num-months="<?= $numMonths; ?>" data-location="<?= $event->get_location(); ?>">
							<?php if ($show_codes) { ?>
								<td class="<?= $courseCodeClass; ?>">
									<?php if (!empty($registrationPage)) { ?>
										<a href="<?= $courseUrl; ?>" class="<?= $courseLinkClass; ?>"><?= $event->get_course_code(); ?></a>
									<?php } else { ?>
										<?= $event->get_course_code(); ?>
									<?php } ?>
								</td>
							<?php } ?>
							<?php if ($show_names) { ?>
								<td class="<?= $courseNameClass; ?>">
									<?php if (!empty($registrationPage)) { ?>
										<a href="<?= $courseUrl; ?>" class="<?= $courseLinkClass; ?>"><?= $event->get_course_title(); ?></a>
									<?php } else { ?>
										<?= $event->get_course_title(); ?>
									<?php } ?>		
								</td>
							<?php } ?>
							<td class="<?= $courseLocationClass; ?>"><?= $event->get_location(); ?></td>
							<td data-start-date="<?= $dates['start']; ?>"><?= $this->plugin->format_date_span($dates); ?></td>
							<?php if ($show_times) { ?>
								<td><?= $this->plugin->format_time_span($times); ?></td>
							<?php } ?>
							<?php if ($show_prices) { ?>
								<td><?php $this->plugin->display_currency_prices($event, $pricingBasis, $defaultCurrency, $showCurrencyIndicator, $currencyIndicator); ?></td>
							<?php } ?>
							<td>
								<?php if ($event->is_sold_out($translateNumPlaces)) { ?>
									<span class="<?= $soldOutClass; ?>"><?php _e('Sold Out', 'administrate'); ?></span>
								<?php } else { ?>
									<?php if (!empty($registrationPage)) { ?>
										<a href="<?= $this->plugin->get_registration_url($event->get_id()); ?>" class="<?= $registerLinkClass; ?>"><?php _e('Register Now', 'administrate'); ?></a>
									<?php } ?>
									<?php if ($this->plugin->to_boolean($this->plugin->get_option('show_remaining_places', 'event'))) { ?>
										<span class="<?= $placesLeftClass; ?>"><?= $event->get_num_places(); ?> <?= $this->plugin->pluralize_str($event->get_num_places(), __('Place', 'administrate'), __('Places', 'administrate')) . __(' Available!', 'administrate'); ?></span>
									<?php } else { ?>
										<span class="<?= $availableClass; ?>"><?php _e('Available', 'administrate'); ?></span>
									<?php } ?>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
					
				<?php } ?>
					
			<?php } ?>
			
		</tbody>
	</table>
	
	<p class="<?= $this->plugin->add_namespace(array('no', 'rows'), '-'); ?>"><?php _e('There were no events with the selected criteria.', 'administrate'); ?></p>
	
	<?php if (!empty($groupSize) && ($groupSize > 0)) { ?>
		<div id="<?= $this->add_namespace('pager', '-'); ?>">
			<a href="#" class="<?= $this->add_namespace(array('pager', 'first'), '-'); ?>" title="<?php _e('first group', 'administrate'); ?>">&lt;&lt;</a>
			<a href="#" class="<?= $this->add_namespace(array('pager', 'previous'), '-'); ?>" title="<?php _e('previous group', 'administrate'); ?>">&lt;</a>
			<span class="<?= $this->add_namespace(array('pager', 'status'), '-'); ?>"></span>
			<a href="#" class="<?= $this->add_namespace(array('pager', 'next'), '-'); ?>" title="<?php _e('next group', 'administrate'); ?>">&gt;</a>
			<a href="#" class="<?= $this->add_namespace(array('pager', 'last'), '-'); ?>" title="<?php _e('last group', 'administrate'); ?>">&gt;&gt;</a>
		</div>
	<?php } ?>
	
	<?php include($this->plugin->get_path('/views/public/widget_footer.php')); ?>
	
</div>
