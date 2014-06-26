<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>
<?php
	$presenter = new PaginationPresenter($paginator);

	$trans = $environment->getTranslator();
?>

<?php if ($paginator->getLastPage() > 1): ?>
<table class="pagination_links" cellspacing="0" cellpadding="0" align="left"><tr>

		<?php
			//echo $presenter->getPrevious('<'.$trans->trans('app.prev_page'));

			//echo $presenter->getNext($trans->trans('app.next_page').'>');
			
			echo $presenter->render();
		?>
</tr></table>
<?php endif; ?>
