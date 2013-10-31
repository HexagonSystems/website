/**
 * Accordion affect for table rows
 */
function assignTableContentAccordion() {
	$parentOfAccordion = $(".parentOfAccordion");

	$parentOfAccordion.find(".actualAccordion").find(">:last-child").hide();

	$parentOfAccordion.click(
			function() {
				var previousSibling = $(this).find(".actualAccordion").find(
						">:last-child").prev();
				$(this).find(".actualAccordion").find(">:last-child")
						.fadeToggle(500);
				if ($(previousSibling).is(":visible")) {
					$(previousSibling).delay(500).toggle();
				} else {
					$(previousSibling).delay(500).fadeToggle(0);
				}

			}).eq(0).trigger('click');
}

/**
 * Empties the comment section
 */
function emptyTableBody(tableConfig) {
	$(tableConfig['print_location']).children().remove();
}

/**
 * Checks to see if the page of table data has already loaded
 * 
 * @param pageNum
 * @returns {Boolean}
 */
function pageAlreadyLoaded(tableConfig, pageNum) {
	var positionToStartOn = (pageNum - 1) * tableConfig['quantity_per_page'];
	var positionToEndOn = positionToStartOn + tableConfig['quantity_per_page']
			- 1;
	if (tableConfig['content'][positionToStartOn] === undefined
			|| tableConfig['content'][positionToStartOn] === null) {
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
function updateTableContentArray(tableConfig, jsonObject, pageNum) {
	var positionToStartOn = (pageNum - 1) * tableConfig['quantity_per_page'];
	var positionToEndOn = positionToStartOn + tableConfig['quantity_per_page'];

	$.each(jsonObject, function(id) {
		tableConfig['content'][positionToStartOn] = jsonObject[id];
		positionToStartOn++;
	});

	findLastPage(tableConfig);

	return printTableDataInTable(tableConfig, pageNum);
}

function findLastPage(tableConfig) {
	if (tableConfig['content'].length <= tableConfig['quantity_per_page']) {
		tableConfig['last_page'] = 1;
	} else if (tableConfig['content'].length % tableConfig['quantity_per_page']) {
		tableConfig['last_page'] = Math.floor(tableConfig['content'].length
				/ tableConfig['quantity_per_page']) + 1;
	} else {
		tableConfig['last_page'] = tableConfig['content'].length
				/ tableConfig['quantity_per_page'];
	}
}

/**
 * Returns the next items to print out to the screen
 */
function printTableDataInTable(tableConfig, pageNum) {
	// If the page of comments isn't already loaded, load it
	if (pageAlreadyLoaded(tableConfig, pageNum) === false
			&& pageNum != tableConfig['last_page']) {
		return false;
	} else {
		var positionToStartOn = (pageNum - 1)
				* tableConfig['quantity_per_page'];
		var positionToEndOn = positionToStartOn
				+ tableConfig['quantity_per_page'];

		var arrayToLoopOver = tableConfig['content'].concat();

		if (tableConfig['last_page'] > -1
				&& tableConfig['last_page'] == pageNum) {
			arrayToLoopOver = arrayToLoopOver.slice(positionToStartOn);
		} else {
			arrayToLoopOver = arrayToLoopOver.slice(positionToStartOn,
					positionToEndOn);
		}

		emptyTableBody(tableConfig);

		return arrayToLoopOver;
	}

}