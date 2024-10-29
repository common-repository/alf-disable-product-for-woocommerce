(function( $ ) {
	'use strict';

	var dc = $('#unavailability_schedule .alf_dp_schedule_from').length;

	if( $('#_alf_dp_disabled').prop('checked') ){
		$('#unavailability_schedule').removeAttr('style');
	}else{
		$('#unavailability_schedule').hide();
	}

	$('#_alf_dp_disabled').on('change', function(){
		if( $('#_alf_dp_disabled').prop('checked') ){
			$('#unavailability_schedule').removeAttr('style');
		}else{
			$('#unavailability_schedule').hide();
		}
	});

	$('#unavailability_schedule .add-field').on('click', function(){
		let error = false;

		$('.alf_dp_schedule.has-error').removeClass('has-error');
		error = alf_sched_validate();
		/*console.log(error);*/
		if( error === false ){
			$('#unavailability_schedule .dup').append(`<span class='dfield'><input type="text" name="alf_dp_schedule[${dc}][from]" placeholder="From (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_from" size="6" /> 
			<input type="text" name="alf_dp_schedule[${dc}][to]" placeholder="To (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_to" size="6" />
			<label for="alf_dp_schedule[${dc}][annual]"><br/><input type="checkbox" class="checkbox" name="alf_dp_schedule[${dc}][annual]" value="1" /></label>
			<button type="button" class="rmv-field">-</button></span>`);
			dc++;
		}
	});

	$(document).on('click', '#unavailability_schedule .rmv-field', function(){
	    $(this).parents('.dfield').remove();
	});

	$(document).on('change', '#unavailability_schedule .alf_dp_schedule', function(){
		alf_single_sched_validate($(this), true);
	});

	function alf_sched_validate(){
		let error = false,
			msg = '';

		$('.alf_dp_schedule.has-error').removeClass('has-error');
		/* Scan All Fields */
		$(".alf_dp_schedule").each(function(i,v){
			let _this = $(v),
				validate = alf_single_sched_validate(_this);

		    if( validate['error'] === true ){
		    	msg = validate['msg'];
		    	error = true;
		    }else{
		    	error = false;
		    }
		});

		if( error == true ){
			alert(msg);
		}

		return error;
	}

	function alf_single_sched_validate( _this, _autoret = false ){
		let val =  $.trim(_this.val()),
			sibling = _this.siblings('.alf_dp_schedule'),
			val2 = $.trim(sibling.val()),
			type = ( _this.hasClass('alf_dp_schedule_from') )? 'from' : 'to',
			ret = {
				'error' : false,
				'msg' : ''
			};

		_this.removeClass('has-error');

		/* Check if valid or has value */
	    if( val.length === 0 ){
	    	_this.addClass('has-error');
	    	ret['msg'] = 'From and To field requires date Format (MM-DD-YYYY).';
	    	ret['error'] = true;
	    }else{
	    	val = new Date(val);
	    	if( val == 'Invalid Date' ){
	    		_this.addClass('has-error');
	    		ret['msg'] = 'From and To field requires date Format (MM-DD-YYYY).';
	    		ret['error'] = true;
	    	}else if( val2.length > 0 ){ /* Check If the sibling field has value */
	    		val2 = new Date(val2);
	    		if ( type == 'to' &&  val < val2 || type == 'from' && val > val2 ) {
	    			_this.addClass('has-error');
	    			/*sibling.addClass('has-error');*/
	    			sibling.val(_this.val());
	    			ret['msg'] = 'From date value must not be later than To value.';
	    			ret['error'] = true;
	    		}
	    	}
	    }

	    if( _autoret === true && ret['error'] == true ){
	    	alert(ret['msg']);
	    }else{
	    	return ret;
	    }
	}
})( jQuery );
