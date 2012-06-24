String.prototype.trim = function()
{
	return this.replace(/^\s+|\s+$/g, "");
}

$(function()
{
	var show = function(id)
	{
		$("#" + id).removeClass("hidden");
	};

	var hide = function(id)
	{
		$("#" + id).addClass("hidden");
	};

	var show_or_hide = function(id_field)
	{
		return $("#" + id_field).val() == -1 ? show : hide;
	};

	var build_option = function(val, text)
	{
		return '<option value="' + val + '">' + text + '</option>';
	}

	var build_option_list = function(id, new_text, transformer, json)
	{
		var options = [build_option(-1, new_text)];
		for (var i = 0; i < json.length; ++i)
		{
			options.push(transformer(json[i]));
		}
		$("#" + id).html(options.join(''));
	}

	var set_error_label = function(id)
	{
		$("label[for='" + id + "']").addClass("error");
	};

	var clear_error_label = function(id)
	{
		$("label[for='" + id + "']").removeClass("error");
	};

	var validate_field_non_empty = function(input_id)
	{
		if ($("#" + input_id).val().trim().length == 0)
		{
			set_error_label(input_id);
			return false;
		}
		clear_error_label(input_id);
		return true;
	};

	var validate_combo_box = function(combo_id, field_validator)
	{
		return ($("#" + combo_id).val() != -1) || field_validator();
	};

	var set_copy = function(data)
	{
		$("#copy_url").val(data.url);
		show("copy_site_field");
		$("#copy_site").val(data.site.siteid);
		show("copy_format_field");
		$("#copy_format").val(data.format);
	};
	var reset_copy = function()
	{
		hide("copy_site_field");
		$("#copy_site").val(-1);
		hide("copy_format_field");
		$("#copy_format").val('');
	};
	var validate_copy = function()
	{
		return validate_field_non_empty('copy_url')
			&& validate_combo_box('copy_site', validate_site)
			&& validate_field_non_empty('copy_format');
	};

	var set_bitsavers = function(data)
	{
		$("#bitsavers_directory").val(data.bitsavers_directory);
	};

	var reset_site = function()
	{
		$("#site_name").val('');
		$("#site_url").val('');
		$("#site_description").val('');
		$("#site_copy_base").val('');
		$("#site_low").val(false);
		$("#site_live").val(false);
	};
	var validate_site = function()
	{
		return validate_field_non_empty("site_name")
			&& validate_field_non_empty("site_url")
			&& validate_field_non_empty("site_copy_base");
	};

	var set_company = function(data)
	{
		show("company_fields");
		$("#company_id").val(data.company);
		show_hide_company_fields();
	};
	var show_hide_company_fields = function()
	{
		var fn = show_or_hide("company_id");
		fn("company_name_field");
		fn("company_name_field");
		fn("company_short_name_field");
		fn("company_sort_name_field");
		fn("company_notes_field");
	};
	var reset_company = function()
	{
		hide("company_fields");
		$("#company_id").val(-1);
	};
	var validate_company = function()
	{
		return validate_field_non_empty("company_name")
			&& validate_field_non_empty("company_short_name")
			&& validate_field_non_empty("company_sort_name");
	};

	var set_publication = function(data)
	{
		show("publication_fields");
		$("#pub_history_ph_title").val(data.title);
		$("#pub_history_ph_pubdate").val(data.pub_date);
		$("#pub_history_ph_part").val(data.part);

		$("#pub_search_keywords").val(data.title);
		search_for_publications();

		$("#supersession_search_keywords").val(data.title);
		search_for_supersessions();
	};
	var reset_publication = function()
	{
		hide("publication_fields");
		$("#pub_history_ph_title").val('');
		$("#pub_history_ph_pubdate").val('');
		$("#pub_history_ph_part").val('');

		$("#pub_search_keywords").val('');

		$("#supersession_search_keywords").val('');
	};
	var validate_publication = function()
	{
		return validate_field_non_empty("pub_history_ph_title");
	};

	var set_supersessions = function(json)
	{
		var set_supersession_pub = function(id)
		{
			build_option_list(id, "(None)",
				function(item)
				{
					return build_option(item.pub_id, item.ph_title);
				},
				json);
		};
		set_supersession_pub("supersession_old_pub");
		set_supersession_pub("supersession_new_pub");
	};
	var reset_supersessions = function()
	{
		var reset_option_list = function(id)
		{
			build_option_list(id, "(None)", null, []);
		};
		reset_option_list("supersession_old_pub");
		reset_option_list("supersession_new_pub");
	};
	var validate_supersession = function()
	{
		return true;
	}

	var set_publication_search_results = function(json)
	{
		build_option_list("pub_pub_id", "(New Publication)",
			function(pub)
			{
				return build_option(pub.ph_pub, pub.ph_title);
			},
			json);
	}
	var reset_publication_search_results = function()
	{
		build_option_list("pub_pub_id", "(New Publication)", null, []);
	};

	var wizard_service = function(data, callback)
	{
		$.post("url-wizard-service.php", data, callback, "json");
	};

	var pub_search = function(search_keywords, callback)
	{
		var company_id = $("#company_id").val();
		if (company_id != -1)
		{
			wizard_service(
				{
					method: "pub-search",
					company: $("#company_id").val(),
					keywords: search_keywords
				},
				callback);
		}
	};

	var search_for_publications = function()
	{
		pub_search($("#pub_search_keywords").val(), set_publication_search_results);
	};

	var search_for_supersessions = function()
	{
		pub_search($("#supersession_search_keywords").val(), set_supersessions);
	};

	var validate_data = function()
	{
		return validate_copy()
			&& validate_combo_box('company_id', validate_company)
			&& validate_combo_box('pub_pub_id', validate_publication)
			&& validate_combo_box("supersession_old_pub", validate_supersession);
	};

	var display_error_message = function()
	{
	};

	$("#copy_url").change(
		function()
		{
			var url = $("#copy_url").val();
			if (url.length > 0)
			{
				wizard_service(
					{
						'method': "url-lookup",
						'url': url
					},
					function(json)
					{
						set_copy(json);
						set_bitsavers(json);
						show_or_hide("copy_site")("site_fields");
						set_company(json);
						set_publication(json);
						show("supersession_fields");
					});
			}
			else
			{
				reset_copy();
				reset_site();
				reset_company();
				reset_publication();
				hide("supersession_fields");
				reset_publication_search_results();
				reset_supersessions();
			}
		});

	$("#copy_site").change(
		function()
		{
			show_or_hide("copy_site")("site_fields");
		});

	$("#company_id").change(show_hide_company_fields);

	$("#supersession_search_keywords").change(search_for_supersessions);
	$("#supersession_old_pub").change($("#supersession_new_pub").val(-1));
	$("#supersession_new_pub").change($("#supersession_old_pub").val(-1));

	$("#pub_search_keywords").change(search_for_publications);

	$("#pub_pub_id").change(
		function()
		{
			var fn = show_or_hide("pub_pub_id");
			fn("pub_history_ph_title_field");
			fn("pub_history_ph_revision_field");
			fn("pub_history_ph_pubtype_field");
			fn("pub_history_ph_pubdate_field");
			fn("pub_history_ph_abstract_field");
			fn("pub_history_ph_part_field");
			fn("pub_history_ph_match_part_field");
			fn("pub_history_ph_sort_part_field");
			fn("pub_history_ph_alt_part_field");
			fn("pub_history_ph_match_alt_part_field");
			fn("pub_history_ph_keywords_field");
			fn("pub_history_ph_notes_field");
			fn("pub_history_ph_class_field");
			fn("pub_history_ph_amend_pub_field");
			fn("pub_history_ph_amend_serial_field");
		});

	$("input[name='next']").click(
		function(event)
		{
			try
			{
				if (!validate_data())
				{
					$('.form_container').after('<p class="error">There is an error!</p>');
					event.preventDefault();
				}
			}
			catch (e)
			{
				$('.form_container').after('<p>There was an exception!  FUCK!</p>' 
					+ '<dl><dt>' + e.name + '</dt><dd>' + e.message + '</dd></dl>');
				event.preventDefault();
			}
		});
});