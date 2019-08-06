<?php if (config('show_swiper')) { ?>
	<div class="block hui-swiper"
		  data-swiper="true"
		  data-timing="fade"
		  data-autoplay="true"
		  data-indicator="true">
		<div class="hui-swiper-items">
			{each:swipers as swiper}
			<div class="hui-swiper-item">
				{:swiper}
			</div>
			{endeach}
		</div>
	</div>
<?php } ?>
