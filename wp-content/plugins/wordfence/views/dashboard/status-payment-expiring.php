<?php
if (!defined('WORDFENCE_VERSION')) { exit; }
/**
 * Expects $id, $title, $subtitle, and $link, and $linkLabel to be defined.
 * If $linkLabel is null, the link will be hidden.
 * $linkNewWindow can optionally be defined and defaults to false.
 */

if (!isset($linkNewWindow)) { $linkNewWindow = false; }
?>
<div id="<?php echo esc_attr($id); ?>" class="wf-status-detail"> 
	<div class="wf-status-payment-expiring">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 644"><g><path style="fill:none" d="M66.3,269.5v205.4c0,2.9,1.1,5.5,3.2,7.6c2.1,2.1,4.7,3.2,7.6,3.2h413.6c8.8-72,65.8-129.1,137.8-138v-78.2
		H66.3z M196,442.4h-86.5l0-43.2H196V442.4z M369,442.4H239.3v-43.2H369V442.4z"/><path style="fill:none" d="M628.4,64.1c0-2.9-1.1-5.4-3.2-7.6c-2.1-2.1-4.7-3.2-7.6-3.2H77.1c-2.9,0-5.5,1.1-7.6,3.2s-3.2,4.7-3.2,7.6
		v75.7h562.1V64.1z"/><g><path style="fill:none" d="M617.6,53.3H77.1c-2.9,0-5.5,1.1-7.6,3.2s-3.2,4.7-3.2,7.6v75.7h562.1V64.1c0-2.9-1.1-5.4-3.2-7.6
			C623.1,54.3,620.5,53.3,617.6,53.3z"/><path d="M655.8,25.9C645.2,15.3,632.5,10,617.6,10H77.1c-14.9,0-27.6,5.3-38.2,15.9C28.3,36.5,23,49.2,23,64.1v410.8
			c0,14.9,5.3,27.6,15.9,38.2c10.6,10.6,23.3,15.9,38.2,15.9h414.1c-1.2-7.8-1.8-15.8-1.8-23.9c0-6.5,0.4-13,1.2-19.3H77.1
			c-2.9,0-5.5-1.1-7.6-3.2c-2.1-2.1-3.2-4.7-3.2-7.6V269.5h562.1v78.2c6.4-0.8,12.9-1.2,19.5-1.2c8.1,0,16,0.6,23.7,1.8V64.1
			C671.7,49.2,666.4,36.5,655.8,25.9z M628.4,139.7H66.3V64.1c0-2.9,1.1-5.4,3.2-7.6s4.7-3.2,7.6-3.2h540.5c2.9,0,5.5,1.1,7.6,3.2
			c2.1,2.1,3.2,4.7,3.2,7.6V139.7z"/><rect x="109.5" y="399.2" width="86.5" height="43.2"/><rect x="239.3" y="399.2" width="129.7" height="43.2"/></g><g><path d="M759.7,440.3c-11.5-19.8-27.2-35.4-46.9-46.9C693,381.8,671.4,376,648,376s-45,5.8-64.7,17.3
			c-19.8,11.5-35.4,27.2-46.9,46.9C524.8,460,519,481.6,519,505c0,23.4,5.8,45,17.3,64.7c11.5,19.8,27.2,35.4,46.9,46.9
			C603,628.2,624.6,634,648,634s45-5.8,64.7-17.3c19.8-11.5,35.4-27.2,46.9-46.9C771.2,550,777,528.4,777,505
			C777,481.6,771.2,460,759.7,440.3L759.7,440.3z M669.5,585.5c0,1.6-0.5,2.9-1.5,3.9c-1,1.1-2.2,1.6-3.7,1.6H632
			c-1.5,0-2.7-0.6-3.9-1.7c-1.1-1.1-1.7-2.4-1.7-3.9v-31.9c0-1.5,0.6-2.7,1.7-3.9c1.1-1.1,2.4-1.7,3.9-1.7h32.2
			c1.5,0,2.7,0.5,3.7,1.6c1,1.1,1.5,2.4,1.5,3.9V585.5z M669.1,527.7c-0.1,1.1-0.7,2.1-1.8,2.9c-1.1,0.8-2.4,1.3-3.9,1.3h-31.1
			c-1.6,0-2.9-0.4-4-1.3c-1.1-0.8-1.7-1.8-1.7-2.9l-2.9-104.3c0-1.3,0.6-2.3,1.7-3c1.1-0.9,2.5-1.3,4-1.3h36.9c1.6,0,2.9,0.5,4,1.3
			c1.1,0.7,1.7,1.7,1.7,3L669.1,527.7z M669.1,527.7"/></g></g></svg>
	</div>
	<p class="wf-status-detail-title"><?php echo esc_html($title); ?></p>
	<p class="wf-status-detail-subtitle"><?php echo esc_html($subtitle); ?></p>
	<p class="wf-status-detail-link"><?php if ($linkLabel !== null): ?><a href="<?php echo esc_attr($link); ?>"<?php echo ($linkNewWindow ? ' target="_blank" rel="noopener noreferrer"' : ''); ?>><?php echo esc_html($linkLabel); ?></a><?php endif; ?></p>
</div>
