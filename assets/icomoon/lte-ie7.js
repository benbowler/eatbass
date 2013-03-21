/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-google-plus' : '&#xe003;',
			'icon-google-plus-2' : '&#xe004;',
			'icon-facebook' : '&#xe001;',
			'icon-twitter' : '&#xe002;',
			'icon-youtube' : '&#xe005;',
			'icon-html5' : '&#xe006;',
			'icon-last' : '&#xe007;',
			'icon-upload' : '&#xe008;',
			'icon-cog' : '&#xe009;',
			'icon-heart' : '&#xe00a;',
			'icon-soundcloud' : '&#xe00b;',
			'icon-tumblr' : '&#xe00c;',
			'icon-heart-2' : '&#xe00d;',
			'icon-heart-broken' : '&#xe00e;',
			'icon-thumbs-up' : '&#xe00f;',
			'icon-stats' : '&#xe010;',
			'icon-pinterest' : '&#xe012;',
			'icon-bubbles' : '&#xe013;',
			'icon-users' : '&#xe015;',
			'icon-new-tab' : '&#xe016;',
			'icon-trophy' : '&#xe017;',
			'icon-sad' : '&#xe011;',
			'icon-play' : '&#xe014;',
			'icon-help' : '&#xe018;',
			'icon-info' : '&#xe019;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; i < els.length; i += 1) {
		el = els[i];
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};