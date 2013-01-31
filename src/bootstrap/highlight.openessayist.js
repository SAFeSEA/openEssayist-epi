/**
 * Split an array in chunks of equal size
 * 
 */
Array.prototype.chunk = function(chunkSize) {
    var array=this;
    return [].concat.apply([],
        array.map(function(elem,i) {
            return i%chunkSize ? [] : [array.slice(i,i+chunkSize)];
        })
    );
};

/**
 * Define the pseudo language for the openEssayist keywords, as used by HighlightJS
 * @see HighlightJS
 */
hljs.LANGUAGES['openessay'] = function(hljs) {
	
	// Should be defined in the document calling the script
	if (typeof hljs.OPENESSAYIST_KEYWORDS === 'undefined')
	{
	    // variable is undefined, set default value
		hljs.OPENESSAYIST_KEYWORDS = "";
	}
	if (typeof hljs.OPENESSAYIST_KEYWORDS_CATEGORIES === 'undefined') {
	    // variable is undefined, set default value
		hljs.OPENESSAYIST_KEYWORDS_CATEGORIES = 3;
	}
	
	// split the keyword string into arrays
	keywords = hljs.OPENESSAYIST_KEYWORDS.split(" ");
	kwChunks = keywords.chunk(6);

	// define the formatters, based on pattern matching
	var formatters = [];
	for (var i=0;i<kwChunks.length;i++)
	{
		formatters.push({
			className: 'class class'+ Math.min(i+1,hljs.OPENESSAYIST_KEYWORDS_CATEGORIES),
	        begin: '\\b(' + (kwChunks[i].join("|")) + ')\\b\\s*\\b(' + (kwChunks[i].join("|")) + ')\\b\\s*',
	        //begin: '\\b(' + (temp) + ')\\b\\s*',
	        keywords: kwChunks[i].join(" "),
	        relevance: 0
		});
	}

	// return the "language" definition
	return {
		keywords: 
		{
			//keyword: hljs.OPENESSAYIST_KEYWORDS
		},
		contains: formatters
	};
	
}(hljs);
