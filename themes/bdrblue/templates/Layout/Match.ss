$MonthJumper
<p><a href="$Parent.Link">&laquo; Back to $Parent.Title</a></p>
<div class="vevent">
  <h3 class="summary">$Title</h3>

  <% with CurrentDate %>
  <p class="dates">$DateRange<% if StartTime %> $TimeRange<% end_if %></p>
  <p class="location">$Up.Location</p>
  <% end_with %>
  
  <div style="float: right"><% include AttendanceList %></div>

  <% if $Result %>
  <div id="result" class=$Result>
    <h3>Black Doris Rovers $BDRScore - $OppositionScore $Opposition</h3>
  </div>
  <% else %>
    $AttendanceForm
  <% end_if %>
  
  <% if OtherDates %>
  <div class="event-calendar-other-dates">
    <h4><% _t('CalendarEvent.ADDITIONALDATES','Additional Dates for this Event') %></h4>
    <ul>
      <% loop OtherDates %>
      <li><a href="$Link" title="$Event.Title">$DateRange<% if StartTime %> $TimeRange<% end_if %></a></li>
      <% end_loop %> 
    </ul>
  </div>
  <% end_if %>
</div>
$Form
$PageComments