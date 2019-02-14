this["WPML_core"] = this["WPML_core"] || {};

this["WPML_core"]["templates/taxonomy-translation/copy-all-popup.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="icl_tt_form wpml-dialog" id="icl_tt_form_' +
((__t = ( trid + '_' + lang )) == null ? '' : __t) +
'" title="' +
((__t = ( labels.copyToAllLanguages )) == null ? '' : __t) +
'">\r\n\t<div class="wpml-dialog-body wpml-dialog-translate ">\r\n\r\n\t\t<p class="wpml-dialog-cols-icon">\r\n\t\t\t<i class="otgs-ico-copy wpml-dialog-icon-xl"></i>\r\n\t\t</p>\r\n\r\n\t\t<div class="wpml-dialog-cols-content">\r\n\t\t\t<p>\r\n\t\t\t\t' +
((__t = ( copyMessage )) == null ? '' : __t) +
'\r\n\t\t\t</p>\r\n\t\t\t<label><input type="checkbox" name="overwrite"> ' +
((__t = ( labels.copyAllOverwrite )) == null ? '' : __t) +
'</label>\r\n\t\t</div>\r\n\t\t<div class="wpml-dialog-footer ">\r\n\t\t\t<span class="errors icl_error_text"></span>\r\n\t\t\t<input class="cancel wpml-dialog-close-button alignleft" value="' +
((__t = ( labels.cancel )) == null ? '' : __t) +
'" type="button">\r\n\t\t\t<input class="button-primary js-copy-all-ok alignright" value="' +
((__t = ( labels.Ok )) == null ? '' : __t) +
'" type="submit">\r\n\t\t\t<span class="spinner alignright"></span>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/filter.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<div class="icl-tt-tools tablenav top clearfix">\r\n\t';
 if ( mode === "translate" ) { ;
__p += '\r\n\t\t' +
((__t = ( WPML_core[ "templates/taxonomy-translation/status-trans-select.html" ]( { taxonomy: taxonomy } ) )) == null ? '' : __t) +
'\r\n\t\t<label for="in-lang" id="in-lang-label" class="hidden">' +
((__t = (labels.in)) == null ? '' : __t) +
'</label>\r\n\t\t\t<select name="language" id="in-lang" class="hidden">\r\n\t\t\t\t<option value="all">' +
((__t = ( labels.anyLang )) == null ? '' : __t) +
'</option>\r\n\t\t\t\t';
 _.each(langs, function( lang, code ) { ;
__p += '\r\n\t\t\t\t\t<option value="' +
((__t = ( code )) == null ? '' : __t) +
'">' +
((__t = ( lang.label )) == null ? '' : __t) +
'</option>\r\n\t\t\t\t';
 }); ;
__p += '\r\n\t\t\t</select>\r\n\t\t<div class="alignright">\r\n\t\t\t<input type="text" name="search" id="tax-search" placeholder="' +
((__t = ( labels.searchPlaceHolder )) == null ? '' : __t) +
'" value="">\r\n\t\t</div>\r\n\t';
 } else { ;
__p += '\r\n\t\t' +
((__t = ( labels.refLang.replace( "%language%", WPML_core[ "templates/taxonomy-translation/ref_sync_select.html" ]( { taxonomy:taxonomy, langs:langs } ) ) )) == null ? '' : __t) +
'\r\n\t';
 } ;
__p += '\r\n\t<span class="spinner"></span>\r\n</div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/individual-label.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<a class="icl_tt_label" id="' +
((__t = (taxonomy)) == null ? '' : __t) +
'_' +
((__t = (lang)) == null ? '' : __t) +
'" title="' +
((__t = ( langs[ lang ].label )) == null ? '' : __t) +
': ' +
((__t = ( labels.editTranslation )) == null ? '' : __t) +
'">\r\n\t<i class="otgs-ico-edit"></i>\r\n</a>\r\n<div id="popup-' +
((__t = (lang)) == null ? '' : __t) +
'"></div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/label-popup.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<div class="icl_tt_form wpml-dialog" id="icl_tt_form_' +
((__t = ( taxonomy )) == null ? '' : __t) +
'" title="' +
((__t = ( labels.labelPopupDialogTitle )) == null ? '' : __t) +
'">\r\n\t<div class="wpml-dialog-body wpml-dialog-translate ">\r\n\t\t<header class="wpml-term-translation-header">\r\n\t\t\t<h3 class="wpml-header-original">' +
__e( labels.original ) +
' <span class="wpml-title-flag"><img src="' +
((__t = ( langs[ source_lang ].flag )) == null ? '' : __t) +
'"></span><strong>' +
__e( langs[ source_lang ].label ) +
'</strong></h3>\r\n\t\t\t<h3 class="wpml-header-translation">' +
__e( labels.translationTo ) +
' <span class="wpml-title-flag"><img src="' +
((__t = ( langs[ lang ].flag )) == null ? '' : __t) +
'"></span><strong>' +
__e( langs[ lang ].label ) +
'</strong></h3>\r\n\t\t</header>\r\n\t\r\n\t\t<div class="wpml-form-row">\r\n\t\t\t<label for="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-singular">' +
__e( labels.Singular ) +
'</label>\r\n\t\t\t<input readonly id="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-singular-original" value="' +
__e( originalLabels.singular ) +
'" type="text">\r\n\t\t\t<button class="button-copy button-secondary js-button-copy otgs-ico-copy" title="' +
__e( labels.copyFromOriginal ) +
'"/>\r\n\t\t\t<input class="js-translation js-required-translation" id="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-singular" value="' +
__e( translatedLabels.singular ) +
'" type="text">\r\n\t\t</div>\r\n\t\r\n\t\t<div class="wpml-form-row">\r\n\t\t\t<label for="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-plural">' +
__e( labels.Plural ) +
'</label>\r\n\t\t\t<input readonly id="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-plural-original" value="' +
__e(originalLabels.general ) +
'" type="text">\r\n\t\t\t<button class="button-copy button-secondary js-button-copy otgs-ico-copy" title="' +
__e( labels.copyFromOriginal ) +
'"/>\r\n\t\t\t<input class="js-translation js-required-translation" id="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-plural" value="' +
__e( translatedLabels.general ) +
'" type="text">\r\n\t\t</div>\r\n\r\n\t\t';
 if( slugTranslationEnabled ) { ;
__p += '\r\n\t\t\t<div class="wpml-form-row js-slug-translation-wrapper">\r\n\t\t\t\t<label for="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-slug">' +
__e( labels.Slug ) +
'</label>\r\n\t\t\t\t<input readonly id="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-slug-original" value="' +
__e(originalLabels.slug ) +
'" type="text">\r\n\t\t\t\t<button class="button-copy button-secondary js-button-copy otgs-ico-copy" title="' +
__e( labels.copyFromOriginal ) +
'"/>\r\n\t\t\t\t<input class="js-translation" id="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'-slug" value="' +
__e( translatedLabels.slug ) +
'" type="text">\r\n\t\t\t</div>\r\n\t\t';
 } ;
__p += '\r\n\t\r\n\t\t<div class="wpml-dialog-footer ">\r\n\t\t\t<span class="errors icl_error_text"></span>\r\n\t\t\t<input class="cancel wpml-dialog-close-button alignleft" value="' +
__e( labels.cancel ) +
'" type="button">\r\n\t\t\t<input class="button-primary js-label-save alignright" value="' +
__e( labels.save ) +
'" type="submit">\r\n\t\t\t<span class="spinner alignright"></span>\r\n\t\t</div>\t\r\n\t</div>\r\n</div>\r\n\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/main.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<label for="icl_tt_tax_switch">\r\n\t' +
((__t = (labels.taxToTranslate)) == null ? '' : __t) +
'\r\n\t<select id="icl_tt_tax_switch">\r\n\t\t<option disabled selected> -- ' +
((__t = (labels.taxonomy)) == null ? '' : __t) +
' --</option>\r\n\t\t';
 _.each(taxonomies, function(taxonomy, index){ ;
__p += '\r\n\t\t\t<option value="' +
((__t = (index)) == null ? '' : __t) +
'">\r\n\t\t\t\t' +
((__t = (taxonomy.label)) == null ? '' : __t) +
'\r\n\t\t\t</option>\r\n\t';
 }); ;
__p += '\r\n\t</select>\r\n</label>\r\n<div class="wpml-loading-taxonomy"><span class="spinner is-active"></span>' +
((__t = (labels.preparingTermsData)) == null ? '' : __t) +
'</div>\r\n<div id="taxonomy-translation">\r\n</div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/nav.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<div class="tablenav bottom">\r\n\t<div class="tablenav-pages" id="taxonomy-terms-table-nav">\r\n\t\t<span class="displaying-num">\r\n\t\t\t';
 if(pages > 1) { ;
__p += '\r\n\t\t\t\t' +
((__t = (items)) == null ? '' : __t) +
' ' +
((__t = (labels.items)) == null ? '' : __t) +
'\r\n\t\t\t';
 } else if(pages === 1) {;
__p += '\r\n\t\t\t\t1 ' +
((__t = (labels.item)) == null ? '' : __t) +
'\r\n\t\t\t';
 } ;
__p += '\r\n\t\t</span>\r\n\t\t<a class="first-page ';
 if(page <= 1 ){ ;
__p += ' disabled ';
 } ;
__p += '" href="###" title="' +
((__t = (labels.goToFirstPage)) == null ? '' : __t) +
'">«</a>\r\n\t\t<a href="###" title="' +
((__t = (labels.goToPreviousPage)) == null ? '' : __t) +
'" class="prev-page ';
 if(page < 2 ) {;
__p += ' disabled';
 } ;
__p += '">‹</a>\r\n\t\t<input class="current-page" size="1" value="' +
((__t = (page)) == null ? '' : __t) +
'" title="' +
((__t = (labels.currentPage)) == null ? '' : __t) +
'" type="text"/>\r\n\t\t' +
((__t = ( labels.of )) == null ? '' : __t) +
' <span class="total-pages">' +
((__t = ( pages )) == null ? '' : __t) +
'</span>\r\n\t\t<a class="next-page  ';
 if(page == pages ) {;
__p += ' disabled ';
 } ;
__p += '" href="###" title="' +
((__t = (labels.goToNextPage)) == null ? '' : __t) +
'">›</a>\r\n\t\t<a class="last-page ';
 if(page == pages ) {;
__p += ' disabled ';
 } ;
__p += '" href="###" title="' +
((__t = (labels.goToLastPage)) == null ? '' : __t) +
'">»</a>\r\n\t</div>\r\n</div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/no-terms-found.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<tr>\r\n\t<td colspan="2">\r\n\t\t<h2 class="text-center">' +
((__t = ( message )) == null ? '' : __t) +
'</h2>\r\n\t</td>\r\n</tr>';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/not-translated-label.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<a class="icl_tt_label lowlight" id="' +
((__t = ( taxonomy )) == null ? '' : __t) +
'_' +
((__t = ( lang )) == null ? '' : __t) +
'" title="' +
((__t = ( langs[ lang ].label )) == null ? '' : __t) +
': ' +
((__t = ( labels.addTranslation )) == null ? '' : __t) +
'" >\r\n\t<i class="otgs-ico-add"></i>\r\n</a>\r\n<div id="popup-' +
((__t = ( lang )) == null ? '' : __t) +
'"></div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/original-label-disabled.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<span title="' +
((__t = ( langs[ lang ].label )) == null ? '' : __t) +
': ' +
((__t = ( labels.originalLanguage )) == null ? '' : __t) +
'">\r\n\t<i class="otgs-ico-original"></i>\r\n</span>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/original-label.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<td class="wpml-col-title">\r\n\t<span class="wpml-title-flag"><img src="' +
((__t = ( flag )) == null ? '' : __t) +
'"></span><strong>' +
((__t = ( taxLabel.singular + ' / ' + taxLabel.general )) == null ? '' : __t) +
'</strong>\r\n\t<p>\r\n\t\t';
 if(!langSelector){ ;
__p += '<a href="#" class="js-show-lang-selector">' +
((__t = ( labels.changeLanguage )) == null ? '' : __t) +
'</a>';
 } ;
__p += '\r\n\t\t' +
((__t = ( langSelector )) == null ? '' : __t) +
'\r\n\t</p>\r\n</td>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/original-term-popup.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="icl_tt_form wpml-dialog" id="icl_tt_form_' +
((__t = ( trid + '_' + lang )) == null ? '' : __t) +
'" title="' +
((__t = ( labels.originalTermPopupDialogTitle )) == null ? '' : __t) +
'">\r\n\t<div class="wpml-dialog-body wpml-dialog-translate ">\r\n\t\t<header class="wpml-term-translation-header">\r\n\t\t\t<h3 class="wpml-header-original-no-translation">' +
((__t = ( labels.original )) == null ? '' : __t) +
' <span class="wpml-title-flag"><img src="' +
((__t = ( langs[ lang ].flag )) == null ? '' : __t) +
'"></span><strong>' +
((__t = ( langs[ lang ].label )) == null ? '' : __t) +
'</strong></h3>\r\n\t\t</header>\r\n\t\r\n\t\t<div class="wpml-form-row-no-translation">\r\n\t\t\t<label for="term-name">' +
((__t = ( labels.Name )) == null ? '' : __t) +
'</label >\r\n\t\t\t<input id="term-name" value="' +
((__t = ( term.name )) == null ? '' : __t) +
'" type="text">\r\n\t\t</div>\r\n\r\n\t\t<div class="wpml-form-row-no-translation">\r\n\t\t\t<label for="term-slug">' +
((__t = ( labels.Slug )) == null ? '' : __t) +
'</label>\r\n\t\t\t<input id="term-slug" value="' +
((__t = ( term.slug )) == null ? '' : __t) +
'" type="text">\r\n\t\t</div>\r\n\t\t<div class="wpml-form-row-no-translation">\r\n\t\t\t<label for="term-description">' +
((__t = ( labels.Description )) == null ? '' : __t) +
'</label>\r\n\t\t\t<textarea id="term-description" cols="22" rows="4">' +
((__t = ( term.description )) == null ? '' : __t) +
'</textarea>\r\n\t\t</div>\r\n\t\t<div class="wpml-dialog-footer ">\r\n\t\t\t<span class="errors icl_error_text"></span>\r\n\t\t\t<input class="cancel wpml-dialog-close-button alignleft" value="' +
((__t = ( labels.cancel )) == null ? '' : __t) +
'" type="button">\r\n\t\t\t<input class="button-primary term-save alignright" value="' +
((__t = ( labels.save )) == null ? '' : __t) +
'" type="submit">\r\n\t\t\t<span class="spinner alignright"></span>\r\n\t\t</div>\t\r\n\t</div>\r\n</div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/original-term.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<a class="icl_tt_term_name"\tid="' +
((__t = (trid + '-' + lang)) == null ? '' : __t) +
'">\r\n\t<span class="wpml-title-flag"><img src="' +
((__t = ( langs[ lang ].flag )) == null ? '' : __t) +
'"></span>\r\n\t<strong>\r\n\t\t';
 if(!name){ ;
__p += '\r\n\t\t\t' +
((__t = (labels.lowercaseTranslate)) == null ? '' : __t) +
'\r\n\t\t';
 } else {  ;
__p += '\r\n\t\t\t';
 if ( level > 0 ) { ;
__p += '\r\n\t\t\t\t' +
((__t = (Array(level+1).join('—') + " ")) == null ? '' : __t) +
'\r\n\t\t\t';
 } ;
__p += '\r\n\t\t\t' +
((__t = (name)) == null ? '' : __t) +
'\r\n\t\t';
 } ;
__p += '\r\n\t</strong>\r\n</a>\r\n<div id="' +
((__t = (trid + '-popup-' + lang)) == null ? '' : __t) +
'"></div>\r\n<div class="row-actions">\r\n\t<a class="js-copy-to-all-langs">' +
((__t = ( labels.copyToAllLanguages )) == null ? '' : __t) +
'</a>\r\n</div>\r\n\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/ref_sync_select.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<select id="in-lang" name="language">\r\n\t';
 _.each( langs, function( lang, code ) { ;
__p += '\r\n\t\t<option value="' +
((__t = (code)) == null ? '' : __t) +
'">' +
((__t = ( lang.label )) == null ? '' : __t) +
'</option>\r\n\t';
 }); ;
__p += '\r\n</select>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/status-trans-select.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<div class="alignleft">\r\n\t<label for="status-select">' +
((__t = (labels.Show)) == null ? '' : __t) +
'</label>\r\n\t<select id="status-select" name="status">\r\n\t\t<option value="0">' +
((__t = (labels.all + ' ' + taxonomy.label)) == null ? '' : __t) +
'</option>\r\n\t\t<option value="1">' +
((__t = (labels.untranslated + ' ' + taxonomy.label)) == null ? '' : __t) +
'</option>\r\n\t</select>\r\n</div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/table.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<table class="widefat striped fixed ' +
((__t = (  ( mode !== 'sync' )? 'wpml-tt-table' : 'wpml-tt-sync-table' )) == null ? '' : __t) +
'" id="tax-table-' +
((__t = (tableType)) == null ? '' : __t) +
'">\r\n\t<thead>\r\n\t\t<tr>\r\n\t\t\t';
 if ( mode !== 'sync' ) { ;
__p += '\r\n\t\t\t\t<th class="wpml-col-title">' +
((__t = ( firstColumnHeading )) == null ? '' : __t) +
'</th>\r\n\t\t\t\t<th class="wpml-col-languages">\r\n\t\t\t\t\t';
 _.each(langs, function( lang ) { ;
__p += '\r\n\t\t\t\t\t\t<span title="' +
((__t = ( lang.label )) == null ? '' : __t) +
'"><img src="' +
((__t = ( lang.flag )) == null ? '' : __t) +
'" alt="' +
((__t = ( lang.label )) == null ? '' : __t) +
'"></span>\r\n\t\t\t\t\t';
 }); ;
__p += '\r\n\t\t\t\t</th>\r\n\t\t\t';
 } else { ;
__p += '\r\n\t\t\t\t';
 _.each(langs, function( lang ) { ;
__p += '\r\n\t\t\t\t\t<th class="wpml-col-ttsync">\r\n\t\t\t\t\t\t<span class="wpml-title-flag"><img src="' +
((__t = ( lang.flag )) == null ? '' : __t) +
'" alt="' +
((__t = ( lang.label )) == null ? '' : __t) +
'"></span>' +
((__t = ( lang.label )) == null ? '' : __t) +
'\r\n\t\t\t\t\t</th>\r\n\t\t\t\t';
 }); ;
__p += '\r\n\t\t\t';
 } ;
__p += '\r\n\t\t</tr>\r\n\t</thead>\r\n</table>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/tabs.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<div id="term-table-tab-controls" class="wpml-tabs">\r\n\t<button class="nav-tab ' +
((__t = ( ( mode ==='translate' ? 'nav-tab-active' : '' ) )) == null ? '' : __t) +
'" id="term-table-header">' +
((__t = ( headerTerms )) == null ? '' : __t) +
'</button>\r\n\t';
 if( taxonomy.hierarchical ) {;
__p += '\r\n\t\t<button class="nav-tab ' +
((__t = ( ( mode ==='sync' ? 'nav-tab-active' : '' ) )) == null ? '' : __t) +
'" id="term-table-sync-header">' +
((__t = ( syncLabel )) == null ? '' : __t) +
'</button>\r\n\t';
 } ;
__p += '\r\n</div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/taxonomy-main-wrap.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<div class="wpml-wrap">\n\t';
 if ( mode === 'translate' ) { ;
__p += '\n\t\t<h3 id="term-table-summary">' +
((__t = ( summaryTerms )) == null ? '' : __t) +
'</h3>\n\t\t';
 if ( TaxonomyTranslation.data.resultsTruncated ) { ;
__p += '\n\t\t<div class="icl-admin-message-warning"><p>' +
((__t = ( resultsTruncated )) == null ? '' : __t) +
'</p></div>\n\t\t';
 } ;
__p += '\n\t\t<div id="wpml-taxonomy-translation-filters"></div>\n\t\t<div id="wpml-taxonomy-translation-terms-table"></div>\n\t\t<div id="wpml-taxonomy-translation-terms-nav"></div>\n\n\t\t<h3 id="term-label-summary">' +
((__t = ( labelSummary )) == null ? '' : __t) +
'</h3>\n\t\t';
 if ( TaxonomyTranslation.data.translatedTaxonomyLabels ) { ;
__p += '\n\t\t\t<div id="wpml-taxonomy-translation-labels-table"></div>\n\t\t';
 } else { ;
__p += '\n\t\t\t<div class="otgs-notice notice notice-warning"><p>' +
((__t = ( labels.activateStringTranslation )) == null ? '' : __t) +
'</p></div>\n\t\t';
 } ;
__p += '\n\t';
 } else if ( mode === 'sync' ) { ;
__p += '\n\t\t<div id="wpml-taxonomy-translation-filters"></div>\n\t\t';
 if ( hasContent ) { ;
__p += '\n\t\t\t<div id="wpml-taxonomy-translation-terms-table"></div>\n\t\t\t<div id="wpml-taxonomy-translation-terms-nav"></div>\n\t\t\t<div class="wpml-tt-sync-section">\n\t\t\t\t<div class="wpml-tt-sync-legend">\n\t\t\t\t\t<strong>' +
((__t = ( labels.legend )) == null ? '' : __t) +
'</strong>\n\t\t\t\t\t<span class="wpml-parent-added" style="background-color:#CCFF99;">' +
((__t = ( labels.willBeAdded )) == null ? '' : __t) +
'</span>\n\t\t\t\t\t<span class="wpml-parent-removed" style="background-color:#F55959;">' +
((__t = ( labels.willBeRemoved )) == null ? '' : __t) +
'</span>\n\t\t\t\t</div>\n\t\t\t\t<div class="wpml-tt-sync-action">\n\t\t\t\t\t<input type="submit" class="button-primary button-lg" value="' +
((__t = ( labels.synchronizeBtn )) == null ? '' : __t) +
'" id="tax-apply">\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t';
 } else { ;
__p += '\n\t\t\t<h2 class="text-center">' +
((__t = ( labelSynced )) == null ? '' : __t) +
'</h2>\n\t\t';
 } ;
__p += '\n\t';
 } ;
__p += '\n</div>\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/term-not-synced.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<span class="icl_tt_term_name_sync" id="' +
((__t = (trid + '-' + lang)) == null ? '' : __t) +
'">\r\n\t';
 if ( name ) { ;
__p += '\r\n\t\t' +
((__t = ( parent )) == null ? '' : __t) +
'</br>\r\n\t\t';
 if ( level > 0 ) { ;
__p += '\r\n\t\t\t' +
((__t = ( Array(level+1).join('—') + " " )) == null ? '' : __t) +
'\r\n\t\t';
 } ;
__p += '\r\n\t\t' +
((__t = ( name )) == null ? '' : __t) +
'\r\n\t';
 } ;
__p += '\r\n</span>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/term-not-translated.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<a class="icl_tt_term_name lowlight" id="' +
((__t = ( trid + '-' + lang )) == null ? '' : __t) +
'" title="' +
((__t = ( langs[ lang ].label )) == null ? '' : __t) +
': ' +
((__t = ( labels.addTranslation )) == null ? '' : __t) +
'" >\r\n\t<i class="otgs-ico-add"></i>\r\n</a>\r\n<div id="' +
((__t = ( trid + '-popup-' + lang )) == null ? '' : __t) +
'"></div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/term-original-disabled.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<span title="' +
((__t = ( langs[ lang ].label )) == null ? '' : __t) +
': ' +
((__t = ( labels.originalLanguage )) == null ? '' : __t) +
'">\r\n\t<i class="otgs-ico-original"></i>\r\n</span>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/term-popup.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<div class="icl_tt_form wpml-dialog" id="icl_tt_form_' +
((__t = ( trid + '_' + lang )) == null ? '' : __t) +
'" title="' +
((__t = ( labels.termPopupDialogTitle )) == null ? '' : __t) +
'">\r\n\t<div class="wpml-dialog-body wpml-dialog-translate ">\r\n\t\t<header class="wpml-term-translation-header">\r\n\t\t\t<h3 class="wpml-header-original">' +
((__t = ( labels.original )) == null ? '' : __t) +
' <span class="wpml-title-flag"><img src="' +
((__t = ( langs[ source_lang ].flag )) == null ? '' : __t) +
'"></span><strong>' +
((__t = ( langs[ source_lang ].label )) == null ? '' : __t) +
'</strong></h3>\r\n\t\t\t<h3 class="wpml-header-translation">' +
((__t = ( labels.translationTo )) == null ? '' : __t) +
' <span class="wpml-title-flag"><img src="' +
((__t = ( langs[ lang ].flag )) == null ? '' : __t) +
'"></span><strong>' +
((__t = ( langs[ lang ].label )) == null ? '' : __t) +
'</strong></h3>\r\n\t\t</header>\r\n\t\r\n\t\t<div class="wpml-form-row">\r\n\t\t\t<label for="term-name">' +
((__t = ( labels.Name )) == null ? '' : __t) +
'</label>\r\n\t\t\t<input readonly id="term-name-original" value="' +
((__t = ( original_term.name )) == null ? '' : __t) +
'" type="text">\r\n\t\t\t<button class="button-copy button-secondary js-button-copy otgs-ico-copy" title="' +
((__t = ( labels.copyFromOriginal )) == null ? '' : __t) +
'"/>\r\n\t\t\t<input id="term-name" value="' +
((__t = ( term.name )) == null ? '' : __t) +
'" type="text">\r\n\t\t</div>\r\n\t\r\n\t\t<div class="wpml-form-row">\r\n\t\t\t<label for="term-slug">' +
((__t = ( labels.Slug )) == null ? '' : __t) +
'</label>\r\n\t\t\t<input readonly id="term-slug-original" value="' +
((__t = ( original_term.slug )) == null ? '' : __t) +
'" type="text">\r\n\t\t\t<button class="button-copy button-secondary js-button-copy otgs-ico-copy" title="' +
((__t = ( labels.copyFromOriginal )) == null ? '' : __t) +
'"/>\r\n\t\t\t<input id="term-slug" value="' +
((__t = ( term.slug )) == null ? '' : __t) +
'" type="text">\r\n\t\t</div>\r\n\t\t<div class="wpml-form-row">\r\n\t\t\t<label for="term-description">' +
((__t = ( labels.Description )) == null ? '' : __t) +
'</label>\r\n\t\t\t<textarea readonly id="term-description-original" cols="22" rows="4">' +
((__t = ( original_term.description )) == null ? '' : __t) +
'</textarea>\r\n\t\t\t<button class="button-copy button-secondary js-button-copy otgs-ico-copy" title="' +
((__t = ( labels.copyFromOriginal )) == null ? '' : __t) +
'"/>\r\n\t\t\t<textarea id="term-description" cols="22" rows="4">' +
((__t = ( term.description )) == null ? '' : __t) +
'</textarea>\r\n\t\t</div>\r\n\t\t';
 if ( original_term_meta.length ) { ;
__p += '\r\n\t\t\t<hr>\r\n\t\t\t<label>' +
((__t = ( labels.termMetaLabel)) == null ? '' : __t) +
'</label>\r\n\t\t\t<div class="wpml-form-row">\r\n\t\t\t\t';
 _.each(original_term_meta, function(meta_data){ ;
__p += '\r\n\t\t\t\t\t<label for="term-meta">' +
((__t = ( meta_data.meta_key )) == null ? '' : __t) +
'</label>\r\n\t\t\t\t\t<input readonly value="' +
__e( meta_data.meta_value ) +
'" type="text">\r\n\t\t\t\t\t<button class="button-copy button-secondary js-button-copy otgs-ico-copy" title="' +
((__t = ( labels.copyFromOriginal )) == null ? '' : __t) +
'"/>\r\n\t\t\t\t\t<input name="term-meta" class="term-meta" data-meta-key="' +
((__t = ( meta_data.meta_key )) == null ? '' : __t) +
'" value="' +
__e( term_meta[meta_data.meta_key] ) +
'" type="text">\r\n\t\t\t\t';
 }); ;
__p += '\r\n\t\t\t</div>\r\n\t\t';
 } ;
__p += '\r\n\t\t<div class="wpml-dialog-footer ">\r\n\t\t\t<span class="errors icl_error_text"></span>\r\n\t\t\t<input class="cancel wpml-dialog-close-button alignleft" value="' +
((__t = ( labels.cancel )) == null ? '' : __t) +
'" type="button">\r\n\t\t\t<input class="button-primary term-save alignright" value="' +
((__t = ( labels.save )) == null ? '' : __t) +
'" type="submit">\r\n\t\t\t<span class="spinner alignright"></span>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/term-synced.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<span class="icl_tt_term_name_sync" id="' +
((__t = (trid + '-' + lang)) == null ? '' : __t) +
'">\r\n\t';
 if ( name ) { ;
__p += '\r\n\t\t' +
((__t = ( parent )) == null ? '' : __t) +
'\r\n\t\t';
if ( level > 0 ) { ;
__p += '\r\n\t\t\t</br>\r\n\t\t\t' +
((__t = ( Array(level+1).join('—') + " " )) == null ? '' : __t) +
'\r\n\t\t';
 } ;
__p += '\r\n\t\t' +
((__t = ( name )) == null ? '' : __t) +
'\r\n\t';
 } ;
__p += '\r\n</span>\r\n';

}
return __p
};

this["WPML_core"]["templates/taxonomy-translation/term-translated.html"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<a class="icl_tt_term_name " id="' +
((__t = ( trid + '-' + lang )) == null ? '' : __t) +
'" title="' +
((__t = ( langs[ lang ].label )) == null ? '' : __t) +
': ' +
((__t = ( labels.editTranslation )) == null ? '' : __t) +
'">\r\n\t<i class="otgs-ico-edit"></i>\r\n</a>\r\n<div id="' +
((__t = ( trid + '-popup-' + lang )) == null ? '' : __t) +
'"></div>\r\n';

}
return __p
};