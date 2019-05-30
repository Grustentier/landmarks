// Enables wrapping and addressing svg objects by calling wrapSvg()
// Slightly moified version from: https://github.com/RedBrainLabs/jquery.wrap-svg/blob/master/jquery.wrap-svg.js
(function(jQuery)
{
	var createSvgWrapper, svgWrapper, svg_tag_names, tag_name, wrap_map, _i, _len;
	createSvgWrapper = function(svg_tag_name)
	{
		var key, obj, val, _fn, _ref;
		obj = { _svgEl: null };
		_ref = $("<svg><" + svg_tag_name + "/></svg>").find("" + svg_tag_name)[0];
		_fn = function(key)
		{
			if ((val != null) && (val.baseVal != null))
			{
				return Object.defineProperty(obj, key,
				{
					get: function()
					{
						return this._svgEl[key].baseVal;
					},
					set: function(value)
					{
						return this._svgEl[key].baseVal = value;
					}
				});
			}
			else
			{
				return Object.defineProperty(obj, key,
				{
					get: function()
					{
						return this._svgEl[key];
					},
					set: function(value)
					{
						return this._svgEl[key] = value;
					}
				});
			}
		};

		for (key in _ref)
		{
			val = _ref[key];
			_fn(key);
		}

		return obj;
	};

	svg_tag_names = ['rect', 'circle', 'ellipse', 'line', 'polygon', 'polyline', 'path'];
	wrap_map = {};
	for (_i = 0, _len = svg_tag_names.length; _i < _len; _i++)
	{
		tag_name = svg_tag_names[_i];
		wrap_map[tag_name] = createSvgWrapper(tag_name);
	}

	svgWrapper = function(el)
	{
		this._svgEl = el;
		this.__proto__ = wrap_map[el.tagName];
		return this;
	};

	return jQuery.fn.wrapSvg = function()
	{
		return this.map(function(i, el)
		{
			if (el.namespaceURI === "http://www.w3.org/2000/svg" && (!("_svgEl" in el)))
			{
				return new svgWrapper(el);
			}
			else
			{
				return el;
			}
		});
	};
})(window.jQuery);
