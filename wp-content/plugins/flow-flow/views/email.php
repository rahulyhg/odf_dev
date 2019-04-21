<?php if ( ! defined( 'WPINC' ) ) die;
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */

$logo_url = $this->context['plugin_url'] . $this->context['slug'] . '/assets/logo.png';
?>
<html>
	<head>
		<title>Social Stream Apps Email Template</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<style type="text/css">
			/* CLIENT-SPECIFIC STYLES */
			body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
			table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} /* Remove spacing between tables in Outlook 2007 and up */
			img{-ms-interpolation-mode: bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

			/* RESET STYLES */
			img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
			table{border-collapse: collapse !important;}
			body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}

			/* iOS BLUE LINKS */
			a[x-apple-data-detectors] {
				color: inherit !important;
				text-decoration: none !important;
				font-size: inherit !important;
				font-family: inherit !important;
				font-weight: inherit !important;
				line-height: inherit !important;
			}

			/* MOBILE STYLES */
			@media screen and (max-width: 525px) {

				/* ALLOWS FOR FLUID TABLES */
				.wrapper {
					width: 100% !important;
					max-width: 100% !important;
				}

				/* ADJUSTS LAYOUT OF LOGO IMAGE */
				.logo img {
					margin: 0 auto !important;
				}

				/* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
				.mobile-hide {
					display: none !important;
				}

				.img-max {
					max-width: 100% !important;
					width: 100% !important;
					height: auto !important;
				}

				/* FULL-WIDTH TABLES */
				.responsive-table {
					width: 100% !important;
				}

				/* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
				.padding {
					padding: 10px 5% 15px 5% !important;
				}

				.padding-meta {
					padding: 30px 5% 0px 5% !important;
					text-align: center;
				}

				.padding-copy {
					padding: 10px 5% 10px 5% !important;
					text-align: center;
				}

				.no-padding {
					padding: 0 !important;
				}

				.section-padding {
					padding: 50px 15px 50px 15px !important;
				}

				/* ADJUST BUTTONS ON MOBILE */
				.mobile-button-container {
					margin: 0 auto;
					width: 100% !important;
				}

				.mobile-button {
					padding: 15px !important;
					border: 0 !important;
					font-size: 16px !important;
					display: block !important;
				}

			}

			/* ANDROID CENTER FIX */
			div[style*="margin: 16px 0;"] { margin: 0 !important; }
		</style>
		<!--[if gte mso 12]>
		<style type="text/css">
			.mso-right {
				padding-left: 20px;
			}
		</style>
		<![endif]-->
	</head>
	<body style="margin: 0 !important; padding: 0 !important;">

		<!-- HIDDEN PREHEADER TEXT -->
		<div style="display: none; font-size: 1px; color: #4C1176; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
			Flow-Flow has detected one or more broken feeds on your website.
		</div>

		<!-- HEADER -->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td bgcolor="#4C1176" align="center">
					<!--[if (gte mso 9)|(IE)]>
					<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
						<tr>
							<td align="center" valign="top" width="500">
					<![endif]-->
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="wrapper">
						<tr>
							<td align="left" valign="top" style="padding: 15px 0;" class="logo">
								<a href="https://social-streams.com" target="_blank">
									<img alt="Logo" src="<?php echo $logo_url;?>" width="180" height="75" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
								</a>
							</td>
						</tr>
					</table>
					<!--[if (gte mso 9)|(IE)]>
					</td>
					</tr>
					</table>
					<![endif]-->
				</td>
			</tr>
			<tr>
				<td bgcolor="#ffffff" align="center" style="padding: 25px 15px 50px 15px;" class="section-padding">
					<table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td>
											<!-- COPY -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td align="left" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy"><p>Flow-Flow has detected one or more broken feeds on <a href="<?php echo get_site_url(); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a>. These feeds appear not to be working:</p>
														<?php
														echo '<ul>';
														foreach ($disabled_feeds as $feed){
															$settings = unserialize($feed['settings']);
															echo '<li>' . ucfirst( $settings->type ) . ' - ' . $settings->content . '</li>';
														}
														echo '</ul>';
														?>
														<p>We will continue monitoring your site, and alert you if any feed goes down again</p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="left">
											<!-- BULLETPROOF BUTTON -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td align="left" style="padding-top: 25px;" class="padding">
														<table border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
															<tr>
																<td align="left" style="border-radius: 3px;" bgcolor="#256F9C"><a href="http://docs.social-streams.com/article/140-my-feed-stopped-working" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; border-radius: 3px; padding: 15px 25px; border: 1px solid #256F9C; display: inline-block;" class="mobile-button">Troubleshooting &rarr;</a></td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#FAFAFA" align="center" style="padding: 50px 0px;">
					<!--[if (gte mso 9)|(IE)]>
					<table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
						<tr>
							<td align="center" valign="top" width="500">
					<![endif]-->
					<!-- UNSUBSCRIBE COPY -->
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="max-width: 500px;" class="responsive-table">
						<tr>
							<td align="center" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
								You are receiving this email because you have activated email notifications for broken feeds on Flow-Flow admin panel. You can change this at any time.
								<br><br>
								<a href="http://go.social-streams.com/help" target="_blank" style="color: #666666; text-decoration: none;">Help Center</a>
								<span style="font-family: Arial, sans-serif; font-size: 12px; color: #444444;">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
								<a href="http://docs.social-streams.com" target="_blank" style="color: #666666; text-decoration: none;">Documentation</a>
							</td>
						</tr>
					</table>
					<!--[if (gte mso 9)|(IE)]>
					</td>
					</tr>
					</table>
					<![endif]-->
				</td>
			</tr>
		</table>
	</body>
</html>

