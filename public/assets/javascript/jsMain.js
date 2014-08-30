$(function() {
	var $iExpand = $(".expand");

	$("#expand").click(function(e) {
		e.preventDefault();

		var $iSidebar = $(".sidebar-expanded"),
			$iOverlap = $("#overlap");

		if($iOverlap.length) {
			$iOverlap.remove();

			$iSidebar.css({
				display: 'none'
			});
		}
		else {
			$iSidebar.css({
				display: 'block'
			});

			$("body").append($("<div id='overlap'></div>"));
		}	
	});

	$("#show_boards").click(function(e) {
		$iExpand.click();
		return false;
	});

	$(document).click(function(e) {
		if(!$(e.target).closest($(".sidebar-expanded, .left-sidebar")).length 
			&& $("#overlap").length) {
			$iExpand.click();
		}
	});

	/* подсветка поста */

	function highLightPost(text) {
		text = text || null;
		
		if(!text) {
			var regExp = /(\#.+)/,
				exec = regExp.exec($(location).attr('href'));
		}
		else {
			var exec = [
				text
			];
		}

		var $posts = $(".posts_body");

		if(exec) {
			var e = $posts.find('div.post_number > a:contains("'+ exec[0] +'")'),
				position = e.position();

			if(e.length == 1) {
				$(".highlighted").css({
					backgroundColor: '#eee'
				});

				e.parents('.post').css({
					backgroundColor: '#FFFEC9'
				}).addClass('highlighted');

				$("html, body").animate({
					scrollTop: position.top
				}, 'slow');
			}	
		}
	}

	/* прикрепление ответов к постам */

	function attachAnswers() {
		$(".answer").each(function(i, val) {
			var currentAnswer = $(this).text().replace(/[^0-9]+/, ''),
				self = $(this),
				$post_id = $(".post_id > span");

			$post_id.each(function(c, value) {
				if(parseInt($(value).text()) == currentAnswer) {
					var $post = $(this).parents('.post, .thread_main_content'),
						$answers = $post.find('.post_answers'),
						post_number = self.parents('.post').find('.post_number > a').attr('href');

					self.attr('href', 'an'+ $post.find('.post_number > a').text());

					if(!$answers.length) {
						$post.append($('<div class="post_answers">\
							Ответы: <a href="'+ post_number +'">\
								>> '+ self.parents('.post').find('.post_id > span').text() +'\
							</a>\
						</div>'));
					}
					else {
						$answers.append('<a href="'+ post_number +'">\
							>> '+ self.parents('.post').find('.post_id > span').text() +'\
						</a>');
					}

					return false;
				}
				else if(($post_id.length - 1) == c) {
					/* отмечаем ответ как ссылку на новый тред, если в треде не найден пост с таким ID */
					self.attr({
						href: '/thread/'+ self.text().replace(/[^0-9]+/, ''),
						target: '_blank'
					}).append('\t— тред');
				}
			});
		});
	}
	
	highLightPost();
	attachAnswers();

	/* подсветка поста при клике на линк */

	$(".post_number > a").click(function(e) {
		highLightPost($(this).text());
	});

	$(".post_answers a, .answer").click(function(e) {
		/* открываем линк с новым тредом */
		if($(this).attr('href').match(/^[^\#]+$/)) {
			return true;
		}

		/* подсвечиваем пост с ответом */

		e.preventDefault();

		highLightPost($(this).attr('href').replace(/[^\#]+/, ''));
	});

	$(".quote-post").click(function(e) {
		$(".add_post > form textarea").val('>> '+ $(this).parents('.post, .thread_main_content').find('.post_id > span').text());
		$(".add_post > button").click();
	});

	/* ответ */

	$(".add_post > button").click(function(e) {
		$(this).toggleClass('opened').toggleClass('closed').next().slideToggle('slow', 'swing', function() {
			$("html, body").animate({
				scrollTop: $(document).height()
			}, 'slow');
		}).css({
			display: 'inline-block'
		});
	});

	/* скроллинг для левого меню */

	$("div.scroll").click(function(e) {
		e.preventDefault();

		var html = $("html, body");

		if($(this).find('.scroll-down').length) {
			html.animate({
				scrollTop: $(document).height()
			});
		}
		else {
			html.animate({
				scrollTop: 0
			});
		}
	});

	$(document).scroll(function() {
		if($(window).scrollTop() + $(window).height() >= $(this).height()) {
			$("div.scroll div").removeClass('scroll-down')
				.addClass('scroll-up');
		}
		else {
			$("div.scroll div").removeClass('scroll-up')
				.addClass('scroll-down');			
		}
	});

	/* обновление защитного кода */

	$("#captcha").click(function(e) {
		$(this).attr('src', '/captcha?'+ (new Date()).getTime());
	});

	/* отправка ответа на сервер */

	$(".add_post > form").submit(function(e) {
		e.preventDefault();

		var formData = new FormData($(this)[0]),
			self = $(this);

		formData.append('thread', $("#thread_id > span").text());

		$(".error").remove();

		$.ajax({
			url: '/add_post',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(e) {
				if(e) {
					self.append('<div class="error">'+ e.error +'</div>');
				}
				else {
					location.reload();
				}
			}
		});
	});

	/* изменение размеров изображения */

	$(".post_image, .thread_image").click(function() {
		$(this).toggleClass('post_opened');
	});

	/* создание треда */

	$(".create_thread").click(function(e) {
		var $wrapper = $("#create-thread"),
			$el = $(".thread, footer");

		$wrapper.toggleClass('opened').css('display', 'block');

		if($wrapper.hasClass('opened')) {
			$el.css('display', 'none');
		}
		else {
			$el.css('display', 'block');
			$wrapper.css('display', 'none');
		}
	});

	/* отправка нового треда на сервер */

	$("#create-thread > .big-form > form").submit(function(e) {
		e.preventDefault();

		var reg = /\/([a-z]+)\//i.exec($(location).attr('href')),
			self = $(this),
			formData = new FormData($(this)[0]);

		if(reg) {
			formData.append('board', reg[1]);
			$(".error").remove();

			$.ajax({
				url: '/add_thread',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,				
				success: function(e) {
					if(e) {
						if(e.error) {
							self.append($('<div class="error">'+ e.error +'</div>'));
							return;
						}

						window.location.href = '/thread/'+ e.link;
					}					
				}
			});
		}
	});
});