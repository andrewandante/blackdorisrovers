<h2>$Title</h2>
<% if $RSSTitle %>
<p class="feed"><a href="$Link(rss)"><% _t('SUBSCRIBE','Calendar RSS Feed') %></a></p>
<% end_if %>
<% if $CurrentMember %>
  <div style="float: right; clear:both">$SyncButton</div>
<% end_if %>
$CalendarWidget
<div class="event-calendar-controls"></div>
<h2>$DateHeader</h2>
<% if Events %>
<div id="event-calendar-events">
  <% include EventList %>
</div>
<% else %>
  <p><% _t('NOEVENTS','There are no events') %>.</p>
<% end_if %>
