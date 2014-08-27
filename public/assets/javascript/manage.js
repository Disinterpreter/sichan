$(function() {
	$("#auth").submit(function(e) {
		e.preventDefault();
		
		var $self = $(this);

		$(".error").remove();

		$.post('/manage/check_auth', $(this).serialize(), function(e) {
			if(e) {
				$self.append($("<div class='error'>"+ e.error +"</div>"));
				return;
			}

			window.location.href = '/';
		});
	});

	$(".remove_thread").click(function() {
		var $self = $(this);

		$.post('/manage/remove_thread', 'param='+ $(this).attr('data-id'), function(e) {
			$self.parents('div.thread').slideUp();
		});
	});

	$(".remove_post").click(function() {
		var $self = $(this);

		$.post('/manage/remove_post', 'param='+ $(this).attr('data-id'), function(e) {
			$self.parents('div.post').slideUp();
		});
	});

	$(".fix_thread").click(function() {
		var $self = $(this);

		$.post('/manage/set_fix', 'param='+ $(this).attr('data-id'), function(e) {
			alert('done');
		});
	});
});