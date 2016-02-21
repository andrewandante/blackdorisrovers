<header class="header" role="banner">
	<div class="inner">
		<div class="unit size4of4 lastUnit">
			<a href="$BaseHref" class="brand" rel="home">
				<img class="logo" src="$SiteConfig.Logo.Link">
				<h1>$SiteConfig.Title</h1>
				<% if $SiteConfig.Tagline %>
				<p>$SiteConfig.Tagline</p>
				<% end_if %>
			</a>
			<% if $SearchForm %>
				<span class="search-dropdown-icon">L</span>
				<div class="search-bar">
					$SearchForm
				</div>
			<% end_if %>
			<% include Navigation %>
		</div>
		<% if $CurrentMember %>
		<h3 style="color: darkred; float:left; padding-left:40px">Logged in as $CurrentMember.FirstName</h3>
		<% end_if %>
	</div>
</header>
