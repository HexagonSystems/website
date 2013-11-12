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
	if (pageNum > tableConfig['last_page']) {
		console.log("Page not already loaded, returning false");
		return false;
	}
	var positionToStartOn = (pageNum - 1) * tableConfig['quantity_per_page'];
	var positionToEndOn = positionToStartOn + tableConfig['quantity_per_page']
			- 1;

	for ( var counter = positionToStartOn; counter < positionToEndOn; counter++) {
		if (tableConfig['content'][counter] === undefined
				|| tableConfig['content'][counter] === null) {
			console.log("Page not already loaded, returning false");
			return false;
		}
	}

	console.log("Page already loaded, returning true");
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
function updateTableContentArray(tableConfig, jsonObject, pageNum, unshiftArray) {
	if (unshiftArray == undefined || unshiftArray == false) {
		var positionToStartOn = (pageNum - 1)
				* tableConfig['quantity_per_page'];
		var positionToEndOn = positionToStartOn
				+ tableConfig['quantity_per_page'];

		$.each(jsonObject, function(id) {
			tableConfig['content'][positionToStartOn] = jsonObject[id];
			positionToStartOn++;
		});
	} else {
		$.each(jsonObject, function(id) {
			tableConfig['content'].unshift(jsonObject[id]);
		});
	}

	findLastPage(tableConfig, pageNum);
}

function findLastPage(tableConfig, pageNum) {
	/*
	 * if (tableConfig['content'].length <= tableConfig['quantity_per_page']) {
	 * tableConfig['last_page'] = 1; } else if (tableConfig['content'].length %
	 * tableConfig['quantity_per_page']) { tableConfig['last_page'] =
	 * Math.floor(tableConfig['content'].length /
	 * tableConfig['quantity_per_page']) + 1; } else { tableConfig['last_page'] =
	 * tableConfig['content'].length / tableConfig['quantity_per_page']; }
	 */
	if (tableConfig['last_page'] < pageNum) {
		tableConfig['last_page'] = pageNum;
	}
}

/**
 * Returns the next items to print out to the screen
 */
function printTableDataInTable(tableConfig, pageNum, emptyBeforeReturn) {
	// If the page of comments isn't already loaded, load it
	if (pageAlreadyLoaded(tableConfig, pageNum) === false
			&& pageNum != tableConfig['last_page']) {
		console.log("Page not already loaded, returning false");
		return false;
	} else {
		console.log("Page already loaded... Getting data from memory");
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

		if (emptyBeforeReturn != false) {
			emptyTableBody(tableConfig);
		}

		return arrayToLoopOver;
	}

}