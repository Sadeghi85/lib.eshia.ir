
<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);

	$trans = $environment->getTranslator();
?>

<?php if ($paginator->getLastPage() > 1): ?>
	<ul class="pager">
		<?php
			echo $presenter->getPrevious($trans->trans(sprintf('%s/pagination.previous', Config::get('app_settings.theme'))));

			echo $presenter->getNext($trans->trans(sprintf('%s/pagination.next', Config::get('app_settings.theme'))));
		?>
	</ul>
<?php endif; ?>
