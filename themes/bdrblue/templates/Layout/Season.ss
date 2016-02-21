<h2>$Title</h2>
<% if $RSSTitle %>
<p class="feed"><a href="$Link(rss)"><% _t('SUBSCRIBE','Calendar RSS Feed') %></a></p>
<% end_if %>
<div class="event-calendar-controls">
</div>
<% if $CurrentMember %>
  $SyncButton
<% end_if %>
<h2>$DateHeader</h2>
<% if Events %>
<div id="event-calendar-events">
  <% include EventList %>
</div>
<% else %>
  <p><% _t('NOEVENTS','There are no events') %>.</p>
<% end_if %>
