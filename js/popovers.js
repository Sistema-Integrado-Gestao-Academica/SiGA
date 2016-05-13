$(document).ready(function(){

	$("#approve_offer_list_btn").hover(function(){
		$("#approve_offer_list_btn").popover('show');
	},function(){
		$("#approve_offer_list_btn").popover('hide');
	});

	$("#remove_program_btn").hover(function(){
		$("#remove_program_btn").popover('show');
	},function(){
		$("#remove_program_btn").popover('hide');
	});

	$("#edit_program_btn").hover(function(){
		$("#edit_program_btn").popover('show');
	},function(){
		$("#edit_program_btn").popover('hide');
	});

	$("#alert").hover(function(){
		$("#alert").popover('show');
	},function(){
		$("#alert").popover('hide');
	});

	$("#link_restore_password").hover(function(){
		$("#link_restore_password").popover('show');
	},function(){
		$("#link_restore_password").popover('hide');
	});
});