var wplcApiUrls = {
	blogFeedUrl: 'https://bleeper.io/blog/wp-json/wp/v2/posts',
	statusPageComponentsURL: 'https://bleeper.statuspage.io/api/v2/components.json',
	statusPageIncidentsURL: 'https://bleeper.statuspage.io/api/v2/incidents.json',
	visitorURL: 'https://bleeper-eu-1.eu-4.evennode.com/api/v1/total-visitors-online?api_key='+nifty_api_key
}

function getTotalVisitors() {
	jQuery.getJSON( wplcApiUrls.visitorURL, function( data ) {
		
		

		jQuery('#totalVisitors').html( data );
	});

}

function getBlogPosts() {
	jQuery.getJSON( wplcApiUrls.blogFeedUrl, function( data ) {
		
		const limit = 5;
		let output = '';
		
		for (let i in data){
			if(i >= limit){
				continue;
			}

			const post = data[i];
			const html = `<div class='wplc_post'>
							<div class='wplc_post_title'>${post.title.rendered}</div>
							<p class='wplc_post_excerpt'>${post.excerpt.rendered}</p>
							<div class='wplc_post_readmore'>
								<a href='${post.link}' target='_BLANK' title='${post.title.rendered}'>Read More</a>
							</div>
						</div>`;
			output += html;
		}

		jQuery('#wplc_blog_posts').html( output );
		
	});
}

function getStatusComponents() {
	jQuery.getJSON( wplcApiUrls.statusPageComponentsURL, function( data ) {
		
		let output = '';

		if(typeof data.components !== 'undefined'){
			for(let i in data.components){
				const component = data.components[i];
				const html = `<div class='wplc_module'>${component.name}: <strong class='wplc_module_value'>${component.status}</strong></div>`;
				output += html;
			}
		}

		jQuery('#wplc_status_modules').html( output );
	});
}


function getStatusIncidents() {
	jQuery.getJSON( wplcApiUrls.statusPageIncidentsURL, function( data ) {
		
		let output = '';

		if(typeof data.incidents !== 'undefined'){
			for(let i in data.incidents){
				const incident = data.incidents[i];

				const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				const date = new Date(incident.created_at);

				const last_report = typeof incident.incident_updates !== 'undefined' && typeof incident.incident_updates[0] !== 'undefined' && incident.incident_updates[0].body !== 'undefined' ? incident.incident_updates[0].body : incident.name;

				const html = `<div class='wplc_incident'>${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}: <strong class='wplc_incident_value'>${incident.status}</strong><div class='wplc_incident_info'>${last_report}</div></div>`;
				output += html;
			}
		}

		jQuery('#wplc_status_incidents').html( output );
	});
}

jQuery(document).ready(function($){
	getBlogPosts();
	getStatusComponents();
	getStatusIncidents();
	getTotalVisitors();
});