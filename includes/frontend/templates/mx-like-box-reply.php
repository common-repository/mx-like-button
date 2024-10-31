<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="mx-like-box" id="mx-like-button-<?php bp_activity_comment_id(); ?>" data-post-type="bp-comments">	

	<div class="mx-like-place">

		<div class="mx-like-place-faces">
			<span class="mx-like" title="0">like</span>
			<span class="mx-heart" title="0">heart</span>
			<span class="mx-laughter" title="0">laughter</span>
			<span class="mx-wow" title="0">wow</span>
			<span class="mx-sad" title="0">sad</span>
			<span class="mx-angry" title="0">angry</span>
		</div>
		<div class="mx-like-place-count mx-display-none">0</div>

	</div>

	<button class="mx-like-main-button" data-like-type="like">
		<span>like</span>
	</button>

	<div class="mx-like-other-faces">
		<button class="mx-like-face-like" data-like-type="like">
			<span>like</span>			
		</button>
		<button class="mx-like-face-heart" data-like-type="heart">
			<span>heart</span>
		</button>
		<button class="mx-like-face-laughter" data-like-type="laughter">			
			<span>laughter</span>
		</button>
		<button class="mx-like-face-wow" data-like-type="wow">
			<span>wow</span>
		</button>
		<button class="mx-like-face-sad" data-like-type="sad">
			<span>sad</span>
		</button>
		<button class="mx-like-face-angry" data-like-type="angry">
			<span>angry</span>
		</button>
	</div>

</div>