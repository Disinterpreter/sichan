/* sichan.in jEditor */

$(function() {
	$.fn.editor = function(params) {
		var params = $.extend({

		}, params);

		var self = this;

		Editor.init(this);

		$(".editor-mark").click(function(e) {
			e.preventDefault();
			var text = $(this).text(),
				jObject = self.get(0),
				currentLength = self.val().length;

			self.val(self.val() + text + ' [text] ' + text)
				.focus();

			var tValue = self.val();

			if(jObject.setSelectionRange) {
				var position = {
					start: currentLength + text.length,
					end: tValue.lastIndexOf(text)
				};

				jObject.setSelectionRange(position.start, position.end);
			}
		});
	}

	var Editor = {
		init: function(area) {
			var objects = Editor.get();

			area.before($('<div class="marks-wrapper"></div>'));

			for(var i in objects) {
				$('<a href="#" class="editor-mark">'+ objects[i]['mark'] +'</a>').appendTo(".marks-wrapper");
			}
		},
		get: function() {
			return [
				{ mark: 	'%%' 	}, 	/* spoiler */
				{ mark: 	'**' 	}, 	/* bold */
				{ mark: 	'*' 	}, 	/* italic */
				{ mark: 	'&'		} 	/* strike */
			];
		}
	};
});