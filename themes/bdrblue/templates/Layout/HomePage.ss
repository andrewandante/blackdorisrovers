<div class="content-container unit size3of4 lastUnit">
	<article>
		<% if $Children %>
		<% with $Child %>
			<h1>Coming Up..</h1>
			$Events
			<% loop $UpcomingAnnouncements %>
			<h3>$Title</h3>
			<p>Things</p>
			<% end_loop %>
		<% end_with %>
		<% end_if %>
		<div class="content">$Content</div>
	</article>
</div>
