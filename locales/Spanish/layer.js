
/*
 * 
 * The code below modifies the Spanish JavaScript localization layer to detect when a 
 * translation is e.g. "the %s", where %s is an army, and changes the translation so 
 * that the right sex is used (le/la).
 */
// Keep a copy of the original text() function which we can use.
Locale._text = Locale.text;

// Replace the text function with a version which is sensitive to armies etc.
Locale.text = function(text, args) {
	
	if( args.length > 0 && typeof args[0] === 'string' ) {
		if( args[0].toLowerCase() == "ejército")
		{
			// Army is the first word being fed into this
			
			switch(text) {
			case 'The %s at %s %s':
				text='El %s en %s %s';
				break;
			case 'The %s at %s disband':
				text='El %s en %s es destruido';
				break;
			case 'The %s at %s retreat to %s':
				text='El %s en %s se retira a %s';
				break;
			case ' the %s in %s ':
				text=' el %s en %s ';
				break;
			case 'The %s at %s':
				text='El %s en %s';
				break;
			case 'The %s at %s ':
				text='El %s en %s ';
				break;
			}
		}
		else if( args[0].toLowerCase() == "flotta") 
		{
			// Fleet is the first word being fed into this
			
			switch(text) {
			case 'The %s at %s %s':
				text='La %s en el %s %s';
				break;
			case 'The %s at %s disband':
				text='La %s en %s es destruida';
				break;
			case 'The %s at %s retreat to %s':
				text='La %s en %s ritira a %s';
				break;
			case ' the %s in %s ':
				text=' la %s en %s ';
				break;
			case 'The %s at %s':
				text='La %s en %s';
				break;
			case 'The %s at %s ':
				text='La %s en %s ';
				break;
			}
		}
		else if ( text == ' to %s ' )
		{
			text = ' %s';

			Territories.each(function(p){
				var t=p[1];
				if( t.supply )
					args[0] = args[0].replace(t.name, ' a '+t.name);
				else
					args[0] = args[0].replace(t.name, ' a '+t.name);
			},this);
		}
	}

	return Locale._text(text, args);
}