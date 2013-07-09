var Thread2 = {
    init: function()
	{
	   // Uncomment this to deactivate multi quote:
	   // $('quickreply_multiquote').remove();
	},

	loadMultiQuoted: function()
	{
		if(use_xmlhttprequest == 1)
		{
			this.spinner = new ActivityIndicator("body", {image: imagepath + "/spinner_big.gif"});
			new Ajax.Request('xmlhttp.php?action=get_multiquoted&load_all=1', {method: 'get', onComplete: function(request) {Thread2.multiQuotedLoaded(request); }});
			return false;
		}
		else
		{
			return true;
		}
	},

	multiQuotedLoaded: function(request)
	{
	    //alert(request.responseText);
		if(request.responseText.match(/<error>(.*)<\/error>/))
		{
			message = request.responseText.match(/<error>(.*)<\/error>/);
			if(!message[1])
			{
				message[1] = "An unknown error occurred.";
			}
			if(this.spinner)
			{
				this.spinner.destroy();
				this.spinner = '';
			}
			alert('There was an error fetching the posts.\n\n'+message[1]);
		}
		else if(request.responseText)
		{
			var id = 'message';
			var quotes = request.responseText;
			tinyMCE.insertQuotes(quotes);
		}
		Thread.clearMultiQuoted();
		$('quickreply_multiquote').hide();
		$('quoted_ids').value = 'all';
		if(this.spinner)
		{
			this.spinner.destroy();
			this.spinner = '';
		}
	}
};
Event.observe(document, 'dom:loaded', Thread2.init);