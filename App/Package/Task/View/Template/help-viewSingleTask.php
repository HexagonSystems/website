<strong>Adding an Update (comment)</strong>
<p>
	To create a comment, simply click the 'Add Update' button. <br /> This
	will open another modal like this one.
</p>
<p>An update consists of 3 fields:</p>
<ul>
	<li><strong>Tag - </strong>This provides an easy way to search for
		similar updates on the <a
		href="index.php?location=timesheetPage&action=search">Search Page</a><br />
		Tags should be a short word to give a very high level description of
		what the update is about (Examples: <a
		href="index.php?location=timesheetPage&action=search&tag_searchBy=text&searchFor=tag&tag_value=addedHours">addedHours</a>,
		<a
		href="index.php?location=timesheetPage&action=search&tag_searchBy=text&searchFor=tag&tag_value=fixedError">fixedError</a>,
		<a
		href="index.php?location=timesheetPage&action=search&tag_searchBy=text&searchFor=tag&tag_value=brokeEverythingOhNo!">brokeEverythingOhNo!</a>
	</li>
	<li><strong>Title - </strong>This is simply the title of your update,
		this should give a slightly more detailed description than the tag,
		but try to keep it small.</li>
	<li><strong>Description - </strong>Here is where you should put what
		your actual update is about. Try and give detailed information rather
		than lengthy bland descriptions as there is still a character limit on
		descriptions</li>
</ul>
<p>Once you have filled out all of these details, click 'Add Update'
	inside the modal and watch your comment slide into the table!</p>
<br />
<strong>Add Hours</strong>
<p>
	To add hours to a task, simply click the 'Add Hours' button. <br />
	This will open another modal like this one.
</p>
<p>An update consists of 3 fields:</p>
<ul>
	<li><strong>Date - </strong>The date you worked on. To enter this click
		the field, a date picker should pop up allowing you to select the
		date.</li>

	<li><strong>Hours - </strong>Here you will enter in how many hours you
		worked for that date</li>
	<li><Strong>Comment - </Strong>Provide a short description of what you
		did during this time.</li>
</ul>
<p>Once you have filled out all of these details, click 'Add Hours'
	inside the modal. The Task system will automatically create a comment
	for you with the comment you inputted with a set tag and title. The
	comment should slide in once it has been successfully added to the
	database.</p>
<br />
<strong>Wipe Hours</strong>
<p>
	To wipe hours for a specific day from the Task, click the 'Wipe Hours'
	button.<br /> This will open another modal like this one.
</p>
<p>Wiping hours consists of 2 fields:</p>
<ul>
	<li><strong>Date - </strong>This is the date you want to wipe the hours
		for</li>
	<li><strong>Comment - </strong>Provide a short description for why you
		are wiping the hours.</li>
</ul>
<p>Once you have filled out all of these details, click 'Wipe Hours'
	inside the modal. The Task system will automatically create a comment
	for you with the comment you inputted with a set tag and title. The
	comment should slide in once it has been successfully added to the
	database.</p>
<br />
<strong>Editing a Task</strong>
<p>
	To edit a Task click the 'Edit Task' button.<br /> This will open
	another modal like this one.
</p>
<p>Editing a task consists of 3 fields</p>
<ul>
	<li><strong>Title - </strong>This is the title of your task.</li>
	<li><strong>Description - </strong>Provide a brief description of your
		task here.</li>
	<li><strong>Status - </strong>The current status of the task.</li>
</ul>
<p>
	Once you have finished editing the task, click "Update Task". This will
	automatically close the modal.<br /> Once the changes have been saved
	to the database the page will update to reflect the changes you have
	made.<br /> Please note, there is no need to refresh the page, the page
	will be updated without the need to refresh.
</p>
<br />
<strong>Search by a Tag</strong>
<p>
	To search by a tag, click on any one of the tags in the comment/update
	table.<br /> This will open another modal like this one.
</p>
<p>Search by a tag has 2 options:
<p>
<ul>
	<li><strong>Find in this task - </strong>This will ask the Search page
		to list all of the tags that match the tag that was clicked and are
		also part of the current task you are in.</li>
	<li><strong>Find in all tasks - </strong>This will ask the Search page
		to list all of the tags that match the tag that was clicked, ignoring
		what task they belong to.</li>
</ul>
<p>Clicking either of these buttons will redirect you to the search page
	that will show you the search results.</p>
<br />
<strong>Navigating through the Table</strong>
<p>
	To keep your data costs down (your welcome mobile users) the Task
	system only loads 5 Tasks at a time.<br /> Each time that you click a
	new page using the paginator below the list of comments, 5 more are
	loaded (for the requested page) and then stored locally. This is so we
	don't have to keep loading the comments each time we change page.<br />
	You may notice that each time you load a new page of comments it takes
	a second or 2 to load, then the second time you go to that page it
	loads instantly. That means the Task system is working correctly :).
</p>
