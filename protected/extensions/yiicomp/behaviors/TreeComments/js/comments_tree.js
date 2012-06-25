(function($) {
	$.fn.treeComments = function(options){
	

		var addCommentForm = $(options.addCommentFormSelector);

		$(options.replyLinksSelector).live('click', function(){

			var parentCommentId = /comment_(\d+)/.exec($(this).attr('rel'))[1];

			$(options.parentIdInputSelector).val(parentCommentId);
			
			addCommentForm.find('textarea').val('');

			addCommentForm.find(options.errorsSelector).remove();

			
			var commentContainer = $('#comment_' + parentCommentId);


			if(commentContainer.length > 0) {
				var hentry = commentContainer.find(options.hEntrySelector);

				if(hentry.length > 0 && parentCommentId > 0) {
					hentry.before(addCommentForm);
				} else {
					commentContainer.append(addCommentForm);
				}
			}
			else
			{
				$(this).after(addCommentForm);
			}


			return false;
		});

		addCommentForm.submit(function(){
			var form = $(this);
			var textarea = form.find('textarea');
			var submitButton = form.find("input[type='submit']");

			
			var ajaxData = form.serialize();//Disabled inputs not serialized

			textarea.attr('disabled', 'disabled');
			submitButton.attr('disabled', 'disabled');
			form.addClass('loading');

			$.ajax({
				type: 'POST',
				url: options.ajaxUrl,
				cache: false,
				data: ajaxData,
				dataType: 'json',
				success: function(response){

					if(response.validated) {
						//if first comment, remove "no comments" message
						$(options.noCommentsSelector).remove();

						var parentCommentId = $(options.parentIdInputSelector).val();

						if(parentCommentId == '') {
							parentCommentId = 0;
						}
						var commentContainer = $('#comment_' + parentCommentId);

						var hentry = commentContainer.find(options.hEntrySelector);

						if(hentry.length > 0) {
							hentry.append(response.html);
						} else {
							var commentHtml = options.hEntryBegin + response.html
								+ options.hEntryEnd;

							if(parentCommentId > 0) {
								commentContainer.append(commentHtml);
							} else {
								commentContainer.prepend(commentHtml);
							}
						}

						$(options.commentsCountSelector).each(function(){
							var element = $(this);
							var currentCount = parseInt(element.text());

							if(currentCount == Number.NaN) currentCount = 0;

							element.text(currentCount + 1);
						})
						
					} else {
						form.find(options.errorsSelector).remove();
						form.prepend(response.html);
					}

					form.removeClass('loading');
					textarea.attr('disabled', '');
					submitButton.attr('disabled', '');

					if(response.validated) {
						$("a[rel='comment_0']").click();
					}
				}
			});

			return false;
		});
	}
})(jQuery);