<?php
if (!defined('WORDFENCE_VERSION')) { exit; }
?>
{{if data.canFix}}<a href="#" class="wf-issue-control wf-issue-control-repair"><svg class="wf-issue-control-icon" viewBox="0 0 106.7 106.7"><path d="M104.94,18.77a4,4,0,0,0-1.17-2.93L90.86,2.93a4.25,4.25,0,0,0-5.87,0L1.17,86.75a4.25,4.25,0,0,0,0,5.86l12.91,12.91A4,4,0,0,0,17,106.7a4,4,0,0,0,2.93-1.17L103.77,21.7a4,4,0,0,0,1.17-2.93ZM75.8,37.87l-7-7,19.1-19.1,7,7Zm0,0"/><path d="M14.93,16.68l2-6.39,6.39-2-6.39-2L14.93,0,13,6.39l-6.39,2,6.39,2Zm0,0"/><path d="M31.87,24.77l3.91,12.77L39.7,24.77l12.77-3.91L39.7,16.95,35.78,4.17,31.87,16.95,19.1,20.86Zm0,0"/><path d="M100.31,48.1l-2-6.39-2,6.39-6.39,2,6.39,2,2,6.39,2-6.39,6.39-2Zm0,0"/><path d="M56.64,16.68l2-6.39,6.39-2-6.39-2L56.64,0l-2,6.39-6.39,2,6.39,2Zm0,0"/></svg><span class="wf-issue-control-label"><?php _e('Repair', 'wordfence'); ?></span></a>{{/if}}
