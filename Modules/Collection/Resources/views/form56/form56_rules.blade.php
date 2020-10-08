<script type="text/javascript">
	var now_datex = moment(new Date());
	var current_month = now_datex.format('MM');
	$.fn.currentYear = function(dx){
		var comp_d = [];
				comp_d['val_currentyear'] = 0;
				comp_d['val_discount'] = 0;
				comp_d['val_penaltycurrentyear'] = 0;
	
			current_month = '01';
		switch(dx['type_p']){
			case '0':
				if( current_month < '04'){
					comp_d['val_currentyear'] = dx['assessed_value'] * .01;
					comp_d['val_discount'] = comp_d['val_currentyear'] * .08;
				}else{
					comp_d['val_currentyear'] = dx['assessed_value'] * .01;
				}
				break;
			case '1':
				if(current_month < '04'){
					comp_d['val_currentyear'] = dx['assessed_value'] * .01;
					comp_d['val_currentyear'] = comp_d['val_currentyear'] / 4;
				}else{
					comp_d['val_currentyear'] = dx['assessed_value'] * .01;
					comp_d['val_penaltycurrentyear'] = 0;
				}
				break;
			case '2':
				break;
			case '3':
				break;
			case '4':
				break;
			default:
				break;

		}

		comp_d['val_currentyear'] = comp_d['val_currentyear'].toFixed(2);
		comp_d['val_discount'] = comp_d['val_discount'].toFixed(2);

		return comp_d;
	}

	$.fn.advanceYear = function(dx){
		return ('advanceYear'+dx['type_p']);
	}

	$.fn.previuosYear = function(dx){
		return ('previuosYear'+dx['type_p']);
	}

</script>