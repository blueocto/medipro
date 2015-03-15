<ul class="<?= $this->add_namespace('prices', '-'); ?>">
	<?php foreach ($event->get_prices() as $price) { ?>
		<li class="<?= $price->get_currency(); ?> <?php if ($price->get_currency() == $defaultCurrency) { ?><?= $this->plugin->add_namespace('selected', '-'); ?><?php } ?>">
			<?= $this->format_currency($price->get_display_price($pricingBasis), $price->get_currency(), $showCurrencyIndicator, $currencyIndicator); ?>
		</li>
	<?php } ?>
</ul> 
