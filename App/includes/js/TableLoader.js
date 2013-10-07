/**
 * Accordion affect for table rows
 */
function assignTableContentAccordion()
{
		$parentOfAccordion = $(".parentOfAccordion");

		$parentOfAccordion.find(".actualAccordion").find(">:last-child").hide();
		
		$parentOfAccordion.click(function(){
			var previousSibling = $(this).find(".actualAccordion").find(">:last-child").prev();
			$(this).find(".actualAccordion").find(">:last-child").fadeToggle(500);
			if( $(previousSibling).is(":visible") )
			{
				$(previousSibling).delay(500).toggle();
			}else
			{
				$(previousSibling).delay(500).fadeToggle(0);
			}
			
		    }).eq(0).trigger('click');
}

/**
 * Empties the comment section
 */
function emptyTableBody()
{
	$(TABLE_CONTENT_PRINT_LOCATION).children().remove();
}

/**
 * Checks to see if the page of table data has already loaded
 * 
 * @param pageNum
 * @returns {Boolean}
 */
function pageAlreadyLoaded(pageNum)
{
	var positionToStartOn = ( pageNum - 1 ) * COMMENTS_PER_PAGE;
	var positionToEndOn = positionToStartOn + COMMENTS_PER_PAGE - 1;

	if(tableContent[positionToStartOn] === undefined || tableContent[positionToStartOn] === null)
	{
		return false;
	}

	if(tableContent[positionToEndOn] === undefined || tableContent[positionToEndOn] === null)
	{
		return false;
	}

	/* USED FOR TESTING */
	// alert(arrayOfComments[positionToStartOn]['content']);
	// alert(arrayOfComments[positionToEndOn]['content']);

	return true;
}

/**
 * Updates the Comment Array
 *
 * @param jsonObject
 * @param pageNumber
 * @param quantity
 */
function updateTableContentArray(jsonObject, pageNum) {
	var positionToStartOn = ( pageNum - 1 ) * COMMENTS_PER_PAGE;
	var positionToEndOn = positionToStartOn + COMMENTS_PER_PAGE;
	
	for(var counter = 0; counter < COMMENTS_PER_PAGE; counter++)
	{
		tempCommentArray = jsonObject[counter];
		positionToAdd = positionToStartOn + counter;
		tableContent[positionToAdd] = jsonObject[counter];
	}

	printCommentsInTable(pageNum);
}