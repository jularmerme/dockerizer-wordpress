window.addEventListener('load', readyFunction, false);
loadingMore = false;
objFiltrarById = {};
ajaxPetitions = [];
cleanFilters();

function readyFunction(){
    var btnsLoadMore = document.getElementsByClassName("adkll-loadmore-btn");
    for(var i=0; i< btnsLoadMore.length; i++) {
        btnsLoadMore.item(i).addEventListener('click', function(e){
            e.preventDefault();
            var id = this.parentElement.parentElement.id;
            if(!loadingMore){
                loadingMore = true;
                var page = eval('objConfigShortCode_'+id+'.page');
                page++;
                ajaxLoadMore(id, page, false);
            }
        });
    }
    if(document.getElementsByClassName("adkll-scrollautoload").length == 1){
        document.addEventListener('scroll', scrollLimit);
    }
    else if(document.getElementsByClassName("adkll-scrollautoload").length > 1){
        for(var i=0; i< btnsLoadMore.length; i++) {
            btnsLoadMore.item(i).classList.remove("adkll-noShow");
        }
    }
    // FIRST LOAD
    var adkll_widgets = document.getElementsByClassName("adk-sc-dv");
    for(var i=0; i< adkll_widgets.length; i++) {
        var id = adkll_widgets.item(i).id;
        var autoload = eval('objConfigShortCode_'+id+'.autoload');
        var page = 1;
        if(autoload){
          page = getPageFromURL(id, page);
          filtrarParamsFromURL(id);
          ajaxLoadMore(id, page, false);
        }
    }
}

function getPageFromURL(id, page){
  if(eval('objConfigShortCode_'+id+'.load_pagination')){
    if(eval('objConfigShortCode_'+id+'.pagination_url')){
      var urlPath = window.location.pathname;
      var arrayPath = urlPath.split("/");
      var posPage = arrayPath.length - 1;
      if(arrayPath[posPage] == ""){
        arrayPath.pop();
        posPage = arrayPath.length - 1;
      }
      if(arrayPath[posPage] != ""){
        if(!isNaN(arrayPath[posPage])){
          page = arrayPath[posPage];
        }
      }
    }
  }
  return page;
}
function scrollLimit(){
    var id = document.getElementsByClassName("adk-sc-dv")[0].id;                                          // ONLY CAN BE ONE SHORTCODE FOR LOAD MORE SCROLL
    var results_container_id = eval('objConfigShortCode_'+id+'.results_container_id');
    if(document.getElementById(results_container_id).getElementsByClassName("adkll-theLastPage").length){
      return;
    }
    var winHeight = parseInt(window.outerHeight);
    var posDoc = parseInt(document.getElementsByClassName("adkll-sc-loadmore")[0].offsetTop);
    var scrollPos = parseInt(window.scrollY);
    if(scrollPos >= (posDoc - winHeight)){
        if(!loadingMore){
            loadingMore = true;
            var page = eval('objConfigShortCode_'+id+'.page');
            page++;
            ajaxLoadMore(id, page, false);
        }
    }
}

function ajaxLoadMore(id, page, replaceResults){
    if(window.AbortController) {
      if(ajaxPetitions[id] != undefined){
        ajaxPetitions[id].abort();
      }
      var controller = new AbortController();
      var signal = controller.signal;
      ajaxPetitions[id] = controller;
    }

    if(eval('objConfigShortCode_'+id+'.load_pagination')){
      if(eval('objConfigShortCode_'+id+'.pagination_url')){
        var urlPage = getPageFromURL(id, page);
        var actualUrl = window.location.pathname;
        var arrayPath = actualUrl.split("/");
        var posPage = arrayPath.length - 1;
        if(arrayPath[posPage] == ""){
          arrayPath.pop();
          posPage = arrayPath.length - 1;
        }
        if(arrayPath[posPage] != ""){
          if(!isNaN(arrayPath[posPage])){
            arrayPath.pop();
          }
        }
        var newUrl = arrayPath.join("/");
        window.history.replaceState(actualUrl, document.title, newUrl + "/" + page);
      }
    }
    
    var loadGif = (document.getElementById(id).getElementsByClassName("adkll-sc-loadGif")) ? document.getElementById(id).getElementsByClassName("adkll-sc-loadGif")[0] : null;
    loadGif.classList.remove("adkll-noShow");

    getFiltersbyId(id);

    var f_query = (filter_query) ? filter_query : eval('objConfigShortCode_'+id+'.query');
    var f_post_type = (filter_post_type) ? filter_post_type : eval('objConfigShortCode_'+id+'.post_type');
    var f_tax_query = (filter_tax_query) ? filter_tax_query : eval('objConfigShortCode_'+id+'.tax_query');
    var f_tax_relation = (filter_tax_relation) ? filter_tax_relation : eval('objConfigShortCode_'+id+'.tax_relation');
    var f_meta_query = (filter_meta_query) ? filter_meta_query : eval('objConfigShortCode_'+id+'.meta_query');
    var f_meta_relation = (filter_meta_relation) ? filter_meta_relation : eval('objConfigShortCode_'+id+'.meta_relation');
    var f_year = (filter_year) ? filter_year : eval('objConfigShortCode_'+id+'.year');
    var f_month = (filter_month) ? filter_month : eval('objConfigShortCode_'+id+'.month');
    var f_day = (filter_day) ? filter_day : eval('objConfigShortCode_'+id+'.day');
    var f_post_in = (filter_post_in) ? filter_post_in : eval('objConfigShortCode_'+id+'.post_in');
    var f_post_not_in = (filter_post_not_in) ? filter_post_not_in : eval('objConfigShortCode_'+id+'.post_not_in');
    var f_post_title = (filter_post_title) ? filter_post_title : eval('objConfigShortCode_'+id+'.post_title');
    var f_post_title_like = (filter_post_title_like) ? filter_post_title_like : eval('objConfigShortCode_'+id+'.post_title_like');
    var f_tag = (filter_tag) ? filter_tag : eval('objConfigShortCode_'+id+'.tag');
    var ajaxurl = eval('objConfigShortCode_'+id+'.url');
    
    var data = new FormData();
    data.append('action', 'load_posts_by_ajax');
    data.append('page', page);
    data.append('load_pagination', eval('objConfigShortCode_'+id+'.load_pagination'));
    data.append('security', eval('objConfigShortCode_'+id+'.nonce'));
    data.append('query', f_query);
    data.append('post_type', f_post_type);
    data.append('tax_query', f_tax_query);
    data.append('tax_relation', f_tax_relation);
    data.append('meta_query', f_meta_query);
    data.append('meta_relation', f_meta_relation);
    data.append('orderby', eval('objConfigShortCode_'+id+'.orderby'));
    data.append('order_meta_key', eval('objConfigShortCode_'+id+'.order_meta_key'));
    data.append('display_query', eval('objConfigShortCode_'+id+'.display_query'));
    data.append('posts_first_page', eval('objConfigShortCode_'+id+'.posts_first_page'));
    data.append('posts_per_page', eval('objConfigShortCode_'+id+'.posts_per_page'));
    data.append('post_status', eval('objConfigShortCode_'+id+'.post_status'));
    data.append('post_in', eval('objConfigShortCode_'+id+'.post_in'));
    data.append('post_not_in', eval('objConfigShortCode_'+id+'.post_not_in'));
    data.append('template', eval('objConfigShortCode_'+id+'.template'));
    data.append('no_results', eval('objConfigShortCode_'+id+'.no_results'));
    data.append('year', f_year);
    data.append('month', f_month);
    data.append('day', f_day);
    data.append('post_in', f_post_in);
    data.append('post_not_in', f_post_not_in);
    data.append('post_title', f_post_title);
    data.append('post_title_like', f_post_title_like);
    data.append('tag', f_tag);
    data.append('class_block', eval('objConfigShortCode_'+id+'.class_block'));
    data.append('class_item', eval('objConfigShortCode_'+id+'.class_item'));
    data.append('class_link', eval('objConfigShortCode_'+id+'.class_link'));
    data.append('class_active', eval('objConfigShortCode_'+id+'.class_active'));
    data.append('class_disabled', eval('objConfigShortCode_'+id+'.class_disabled'));
    data.append('cf_before', eval('objConfigShortCode_'+id+'.cf_before'));
    data.append('cf_after', eval('objConfigShortCode_'+id+'.cf_after'));
    
    var results_container_id = (eval('objConfigShortCode_'+id+'.results_container_id')) ? eval('objConfigShortCode_'+id+'.results_container_id') : "";
    var callbackFnBefore = (eval('objConfigShortCode_'+id+'.cf_before')) ? eval('objConfigShortCode_'+id+'.cf_before')+"()" : "";
    var callbackFnAfter = (eval('objConfigShortCode_'+id+'.cf_after'))  ? eval('objConfigShortCode_'+id+'.cf_after')+"()" : "";

    if(!results_container_id){
        console.log("You should set a container_id to display results (results_container_id)");
        return;
    }
    // CALLBACK
    if(callbackFnBefore){
        callbackFnBefore = "window."+callbackFnBefore;
        eval(callbackFnBefore);
    }

    fetch(ajaxurl, {
        method: 'POST',
        body: data,
        signal: signal
    })
    .then(function(response) {
        if(response.ok) {
            loadGif.classList.add("adkll-noShow");
            return response.text();
        } else {
            resultDv.innerHTML = "";
            throw "Ajax call failed";
        }
    })
    .then(function(response) {
        var resultDv = document.getElementById(results_container_id);
        resultDv.innerHTML = resultDv.innerHTML + response;
        if(replaceResults){
            resultDv.innerHTML = response;
        }

        // CLASSIC PAGINATION
        var paginationBar = document.getElementById(results_container_id).getElementsByClassName("adkll-sc-classicPagination")[0];
        
        if(paginationBar){
            btnChangePage = paginationBar.getElementsByClassName("btnChangePage");
            for(var i=0; i< btnChangePage.length; i++) {
                btnChangePage.item(i).addEventListener('click', function(e){
                    e.preventDefault();
                    page = this.attributes.value.nodeValue;
                    ajaxLoadMore(id, page, true);
                });
            }
        }
        
        var btnLoadMore = document.getElementById(id).getElementsByClassName("adkll-loadmore-btn")[0];
        if(btnLoadMore){
            btnLoadMore.classList.remove("adkll-noShow");
        }
        eval("objConfigShortCode_"+id+".page="+page);
        if(document.getElementById(results_container_id).getElementsByClassName("adkll-theLastPage").length){
            if(btnLoadMore){
                btnLoadMore.classList.add("adkll-noShow");
            }
        }
        else{
            setTimeout(allowLoadMore, 300);
        }
        // CALLBACK
        if(callbackFnAfter){
            callbackFnAfter = "window."+callbackFnAfter;
            eval(callbackFnAfter);
        }
    })
    .catch(function(err) {
        if(err.name != "AbortError"){
          if( window.localStorage ) {
            if( !localStorage.getItem('firstLoad') ) {
              localStorage['firstLoad'] = true;
              window.location.reload();
            }  
            else{ 
              localStorage.removeItem('firstLoad');
            }
          }
          console.log(err);
        }
    });
}

function allowLoadMore(){
    loadingMore = false;
}

function filtrarParamsFromURL(id){
    return;
    cleanFilters();
    var fullUrl = window.location.href;
    var fullUrl = fullUrl.split('?');
    if(fullUrl[1] == 'undefined'){
      return;
    }
    var params = fullUrl[1];
    if(!params){
      return;
    }
    if(objFiltrarById[id] == undefined){
      objFiltrarById[id] = new Array();
    }
    eachParam = params.split('&');
    for(var i in eachParam){
      var parameter = eachParam[i].split('=');
      
      if(parameter[0] == 'lm_query'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_query = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_post_type'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_post_type = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_tax_query'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_tax_query = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_tax_relation'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_tax_relation = parameter[1];
        }
      }

      if(parameter[0] == 'lm_meta_query'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_meta_query = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_meta_relation'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_meta_relation = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_year'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_year = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_month'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_month = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_day'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_day = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_post_in'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_post_in = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_post_not_in'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_post_not_in = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_post_title'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_post_title = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_post_title_like'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_post_title_like = parameter[1];
        }
      }
      
      if(parameter[0] == 'lm_tag'){
        if(parameter[1] != 'undefined'){
          objFiltrarById[id].filter_tag = parameter[1];
        }
      }
      
    }

}

function cleanFilters(){
    filter_query = "";
    filter_post_type = "";
    filter_tax_query = "";
    filter_tax_relation = "";
    filter_meta_query = "";
    filter_meta_relation = "";
    filter_year = "";
    filter_month = "";
    filter_day = "";
    filter_post_in = "";
    filter_post_not_in = "";
    filter_post_title = "";
    filter_post_title_like = "";
    filter_tag = "";
}

// objetoFiltrar {query: "query string", post-type: "post,page,custom", taxonomy: "category|slug|books,tags|slug|some-tag"}
function adkll_filter(objetoFiltrar){
    var id = "adkll_defId";
    if(objetoFiltrar.id){
      id = "adkll_"+objetoFiltrar.id;
    }
    if(objFiltrarById[id] == undefined){
      objFiltrarById[id] = new Array();
    }
    if(objetoFiltrar.query != undefined){
      objFiltrarById[id].filter_query = objetoFiltrar.query;
    }
    if(objetoFiltrar.post_type != undefined){
        objFiltrarById[id].filter_post_type = objetoFiltrar.post_type;
    }
    if(objetoFiltrar.tax_query != undefined){
        objFiltrarById[id].filter_tax_query = objetoFiltrar.tax_query;
    }
    if(objetoFiltrar.tax_relation != undefined){
        objFiltrarById[id].filter_tax_relation = objetoFiltrar.tax_relation;
    }
    if(objetoFiltrar.meta_query != undefined){
        objFiltrarById[id].filter_meta_query = objetoFiltrar.meta_query;
    }
    if(objetoFiltrar.meta_relation != undefined){
        objFiltrarById[id].filter_meta_relation = objetoFiltrar.meta_relation;
    }
    if(objetoFiltrar.year != undefined){
        objFiltrarById[id].filter_year = objetoFiltrar.year;
    }
    if(objetoFiltrar.month != undefined){
        objFiltrarById[id].filter_month = objetoFiltrar.month;
    }
    if(objetoFiltrar.day != undefined){
        objFiltrarById[id].filter_day = objetoFiltrar.day;
    }
    if(objetoFiltrar.post_in != undefined){
        objFiltrarById[id].filter_post_in = objetoFiltrar.post_in;
    }
    if(objetoFiltrar.post_not_in != undefined){
        objFiltrarById[id].filter_post_not_in = objetoFiltrar.post_not_in;
    }
    if(objetoFiltrar.post_title != undefined){
        objFiltrarById[id].filter_post_title = objetoFiltrar.post_title;
    }
    if(objetoFiltrar.post_title_like != undefined){
        objFiltrarById[id].filter_post_title_like = objetoFiltrar.post_title_like;
    }
    if(objetoFiltrar.tag != undefined){
        objFiltrarById[id].filter_tag = objetoFiltrar.tag;
    }
    reloadFirst(id);
    rebuildUrlFilters(id);
}

function reloadFirst(id){
    loadingMore = false;

    if(id){
        var results_container_id = eval('objConfigShortCode_'+id+'.results_container_id');
        document.getElementById(results_container_id).innerHTML = "";
        ajaxLoadMore(id, 1, false);
    }
    else{
    var adkll_widgets = document.getElementsByClassName("adk-sc-dv");
        for(var i=0; i< adkll_widgets.length; i++) {
            var id = adkll_widgets.item(i).id;
            var results_container_id = eval('objConfigShortCode_'+id+'.results_container_id');
            document.getElementById(results_container_id).innerHTML = "";
            ajaxLoadMore(id, 1, false);
        }
    }
}

function rebuildUrlFilters(id){
  return;
  var url = "";
  if(objFiltrarById[id] != undefined){
    if(objFiltrarById[id].filter_query){
        url += "&lm_query=" + objFiltrarById[id].filter_query;
    }
    if(objFiltrarById[id].filter_post_type){
        url += "&lm_post_type=" + objFiltrarById[id].filter_post_type;
    }
    if(objFiltrarById[id].filter_tax_query){
        url += "&lm_tax_query=" + objFiltrarById[id].filter_tax_query;
    }
    if(objFiltrarById[id].filter_tax_relation){
        url += "&lm_tax_relation=" + objFiltrarById[id].filter_tax_relation;
    }
    if(objFiltrarById[id].filter_meta_query){
        url += "&lm_meta_query=" + objFiltrarById[id].filter_meta_query;
    }
    if(objFiltrarById[id].filter_meta_relation){
        url += "&lm_meta_relation=" + objFiltrarById[id].filter_meta_relation;
    }
    if(objFiltrarById[id].filter_year){
        url += "&lm_year=" + objFiltrarById[id].filter_year;
    }
    if(objFiltrarById[id].filter_month){
        url += "&lm_month=" + objFiltrarById[id].filter_month;
    }
    if(objFiltrarById[id].filter_day){
        url += "&lm_day=" + objFiltrarById[id].filter_day;
    }
    if(objFiltrarById[id].filter_post_in){
        url += "&lm_post_in=" + objFiltrarById[id].filter_post_in;
    }
    if(objFiltrarById[id].filter_post_not_in){
        url += "&lm_post_not_in=" + objFiltrarById[id].filter_post_not_in;
    }
    if(objFiltrarById[id].filter_post_title){
        url += "&lm_post_title=" + objFiltrarById[id].filter_post_title;
    }
    if(objFiltrarById[id].filter_post_title_like){
        url += "&lm_post_title_like=" + objFiltrarById[id].filter_post_title_like;
    }
    if(objFiltrarById[id].filter_tag){
        url += "&lm_tag=" + objFiltrarById[id].filter_tag;
    }
  }
  url = url.replace("&", "?");
  var fullUrl = window.location.href;
  var BaseUrl = fullUrl.split('?');
  var BaseUrl = BaseUrl[0];
  var newUrl = BaseUrl + url;
  window.history.replaceState(BaseUrl, null, newUrl);
}

function getFiltersbyId(id){
  cleanFilters();
  if(objFiltrarById[id] != undefined){
    if(objFiltrarById[id].filter_query){
        filter_query = objFiltrarById[id].filter_query;
    }
    if(objFiltrarById[id].filter_post_type){
        filter_post_type = objFiltrarById[id].filter_post_type;
    }
    if(objFiltrarById[id].filter_tax_query){
        filter_tax_query = objFiltrarById[id].filter_tax_query;
    }
    if(objFiltrarById[id].filter_tax_relation){
        filter_tax_relation = objFiltrarById[id].filter_tax_relation;
    }
    if(objFiltrarById[id].filter_meta_query){
        filter_meta_query = objFiltrarById[id].filter_meta_query;
    }
    if(objFiltrarById[id].filter_meta_relation){
        filter_meta_relation = objFiltrarById[id].filter_meta_relation;
    }
    if(objFiltrarById[id].filter_year){
        filter_year = objFiltrarById[id].filter_year;
    }
    if(objFiltrarById[id].filter_month){
        filter_month = objFiltrarById[id].filter_month;
    }
    if(objFiltrarById[id].filter_day){
        filter_day = objFiltrarById[id].filter_day;
    }
    if(objFiltrarById[id].filter_post_in){
        filter_post_in = objFiltrarById[id].filter_post_in;
    }
    if(objFiltrarById[id].filter_post_not_in){
        filter_post_not_in = objFiltrarById[id].filter_post_not_in;
    }
    if(objFiltrarById[id].filter_post_title){
        filter_post_title = objFiltrarById[id].filter_post_title;
    }
    if(objFiltrarById[id].filter_post_title_like){
        filter_post_title_like = objFiltrarById[id].filter_post_title_like;
    }
    if(objFiltrarById[id].filter_tag){
        filter_tag = objFiltrarById[id].filter_tag;
    }
  }
}

if(!window.fetch) {
    load_fetchPolyfill();
}

function load_fetchPolyfill(){
    (function (global, factory) {
        typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
        typeof define === 'function' && define.amd ? define(['exports'], factory) :
        (factory((global.WHATWGFetch = {})));
      }(this, (function (exports) { 'use strict';
  
        var support = {
          searchParams: 'URLSearchParams' in self,
          iterable: 'Symbol' in self && 'iterator' in Symbol,
          blob:
            'FileReader' in self &&
            'Blob' in self &&
            (function() {
              try {
                new Blob();
                return true
              } catch (e) {
                return false
              }
            })(),
          formData: 'FormData' in self,
          arrayBuffer: 'ArrayBuffer' in self
        };
  
        function isDataView(obj) {
          return obj && DataView.prototype.isPrototypeOf(obj)
        }
  
        if (support.arrayBuffer) {
          var viewClasses = [
            '[object Int8Array]',
            '[object Uint8Array]',
            '[object Uint8ClampedArray]',
            '[object Int16Array]',
            '[object Uint16Array]',
            '[object Int32Array]',
            '[object Uint32Array]',
            '[object Float32Array]',
            '[object Float64Array]'
          ];
  
          var isArrayBufferView =
            ArrayBuffer.isView ||
            function(obj) {
              return obj && viewClasses.indexOf(Object.prototype.toString.call(obj)) > -1
            };
        }
  
        function normalizeName(name) {
          if (typeof name !== 'string') {
            name = String(name);
          }
          if (/[^a-z0-9\-#$%&'*+.^_`|~]/i.test(name)) {
            throw new TypeError('Invalid character in header field name')
          }
          return name.toLowerCase()
        }
  
        function normalizeValue(value) {
          if (typeof value !== 'string') {
            value = String(value);
          }
          return value
        }
  
        // Build a destructive iterator for the value list
        function iteratorFor(items) {
          var iterator = {
            next: function() {
              var value = items.shift();
              return {done: value === undefined, value: value}
            }
          };
  
          if (support.iterable) {
            iterator[Symbol.iterator] = function() {
              return iterator
            };
          }
  
          return iterator
        }
  
        function Headers(headers) {
          this.map = {};
  
          if (headers instanceof Headers) {
            headers.forEach(function(value, name) {
              this.append(name, value);
            }, this);
          } else if (Array.isArray(headers)) {
            headers.forEach(function(header) {
              this.append(header[0], header[1]);
            }, this);
          } else if (headers) {
            Object.getOwnPropertyNames(headers).forEach(function(name) {
              this.append(name, headers[name]);
            }, this);
          }
        }
  
        Headers.prototype.append = function(name, value) {
          name = normalizeName(name);
          value = normalizeValue(value);
          var oldValue = this.map[name];
          this.map[name] = oldValue ? oldValue + ', ' + value : value;
        };
  
        Headers.prototype['delete'] = function(name) {
          delete this.map[normalizeName(name)];
        };
  
        Headers.prototype.get = function(name) {
          name = normalizeName(name);
          return this.has(name) ? this.map[name] : null
        };
  
        Headers.prototype.has = function(name) {
          return this.map.hasOwnProperty(normalizeName(name))
        };
  
        Headers.prototype.set = function(name, value) {
          this.map[normalizeName(name)] = normalizeValue(value);
        };
  
        Headers.prototype.forEach = function(callback, thisArg) {
          for (var name in this.map) {
            if (this.map.hasOwnProperty(name)) {
              callback.call(thisArg, this.map[name], name, this);
            }
          }
        };
  
        Headers.prototype.keys = function() {
          var items = [];
          this.forEach(function(value, name) {
            items.push(name);
          });
          return iteratorFor(items)
        };
  
        Headers.prototype.values = function() {
          var items = [];
          this.forEach(function(value) {
            items.push(value);
          });
          return iteratorFor(items)
        };
  
        Headers.prototype.entries = function() {
          var items = [];
          this.forEach(function(value, name) {
            items.push([name, value]);
          });
          return iteratorFor(items)
        };
  
        if (support.iterable) {
          Headers.prototype[Symbol.iterator] = Headers.prototype.entries;
        }
  
        function consumed(body) {
          if (body.bodyUsed) {
            return Promise.reject(new TypeError('Already read'))
          }
          body.bodyUsed = true;
        }
  
        function fileReaderReady(reader) {
          return new Promise(function(resolve, reject) {
            reader.onload = function() {
              resolve(reader.result);
            };
            reader.onerror = function() {
              reject(reader.error);
            };
          })
        }
  
        function readBlobAsArrayBuffer(blob) {
          var reader = new FileReader();
          var promise = fileReaderReady(reader);
          reader.readAsArrayBuffer(blob);
          return promise
        }
  
        function readBlobAsText(blob) {
          var reader = new FileReader();
          var promise = fileReaderReady(reader);
          reader.readAsText(blob);
          return promise
        }
  
        function readArrayBufferAsText(buf) {
          var view = new Uint8Array(buf);
          var chars = new Array(view.length);
  
          for (var i = 0; i < view.length; i++) {
            chars[i] = String.fromCharCode(view[i]);
          }
          return chars.join('')
        }
  
        function bufferClone(buf) {
          if (buf.slice) {
            return buf.slice(0)
          } else {
            var view = new Uint8Array(buf.byteLength);
            view.set(new Uint8Array(buf));
            return view.buffer
          }
        }
  
        function Body() {
          this.bodyUsed = false;
  
          this._initBody = function(body) {
            this._bodyInit = body;
            if (!body) {
              this._bodyText = '';
            } else if (typeof body === 'string') {
              this._bodyText = body;
            } else if (support.blob && Blob.prototype.isPrototypeOf(body)) {
              this._bodyBlob = body;
            } else if (support.formData && FormData.prototype.isPrototypeOf(body)) {
              this._bodyFormData = body;
            } else if (support.searchParams && URLSearchParams.prototype.isPrototypeOf(body)) {
              this._bodyText = body.toString();
            } else if (support.arrayBuffer && support.blob && isDataView(body)) {
              this._bodyArrayBuffer = bufferClone(body.buffer);
              // IE 10-11 can't handle a DataView body.
              this._bodyInit = new Blob([this._bodyArrayBuffer]);
            } else if (support.arrayBuffer && (ArrayBuffer.prototype.isPrototypeOf(body) || isArrayBufferView(body))) {
              this._bodyArrayBuffer = bufferClone(body);
            } else {
              this._bodyText = body = Object.prototype.toString.call(body);
            }
  
            if (!this.headers.get('content-type')) {
              if (typeof body === 'string') {
                this.headers.set('content-type', 'text/plain;charset=UTF-8');
              } else if (this._bodyBlob && this._bodyBlob.type) {
                this.headers.set('content-type', this._bodyBlob.type);
              } else if (support.searchParams && URLSearchParams.prototype.isPrototypeOf(body)) {
                this.headers.set('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
              }
            }
          };
  
          if (support.blob) {
            this.blob = function() {
              var rejected = consumed(this);
              if (rejected) {
                return rejected
              }
  
              if (this._bodyBlob) {
                return Promise.resolve(this._bodyBlob)
              } else if (this._bodyArrayBuffer) {
                return Promise.resolve(new Blob([this._bodyArrayBuffer]))
              } else if (this._bodyFormData) {
                throw new Error('could not read FormData body as blob')
              } else {
                return Promise.resolve(new Blob([this._bodyText]))
              }
            };
  
            this.arrayBuffer = function() {
              if (this._bodyArrayBuffer) {
                return consumed(this) || Promise.resolve(this._bodyArrayBuffer)
              } else {
                return this.blob().then(readBlobAsArrayBuffer)
              }
            };
          }
  
          this.text = function() {
            var rejected = consumed(this);
            if (rejected) {
              return rejected
            }
  
            if (this._bodyBlob) {
              return readBlobAsText(this._bodyBlob)
            } else if (this._bodyArrayBuffer) {
              return Promise.resolve(readArrayBufferAsText(this._bodyArrayBuffer))
            } else if (this._bodyFormData) {
              throw new Error('could not read FormData body as text')
            } else {
              return Promise.resolve(this._bodyText)
            }
          };
  
          if (support.formData) {
            this.formData = function() {
              return this.text().then(decode)
            };
          }
  
          this.json = function() {
            return this.text().then(JSON.parse)
          };
  
          return this
        }
  
        // HTTP methods whose capitalization should be normalized
        var methods = ['DELETE', 'GET', 'HEAD', 'OPTIONS', 'POST', 'PUT'];
  
        function normalizeMethod(method) {
          var upcased = method.toUpperCase();
          return methods.indexOf(upcased) > -1 ? upcased : method
        }
  
        function Request(input, options) {
          options = options || {};
          var body = options.body;
  
          if (input instanceof Request) {
            if (input.bodyUsed) {
              throw new TypeError('Already read')
            }
            this.url = input.url;
            this.credentials = input.credentials;
            if (!options.headers) {
              this.headers = new Headers(input.headers);
            }
            this.method = input.method;
            this.mode = input.mode;
            this.signal = input.signal;
            if (!body && input._bodyInit != null) {
              body = input._bodyInit;
              input.bodyUsed = true;
            }
          } else {
            this.url = String(input);
          }
  
          this.credentials = options.credentials || this.credentials || 'same-origin';
          if (options.headers || !this.headers) {
            this.headers = new Headers(options.headers);
          }
          this.method = normalizeMethod(options.method || this.method || 'GET');
          this.mode = options.mode || this.mode || null;
          this.signal = options.signal || this.signal;
          this.referrer = null;
  
          if ((this.method === 'GET' || this.method === 'HEAD') && body) {
            throw new TypeError('Body not allowed for GET or HEAD requests')
          }
          this._initBody(body);
        }
  
        Request.prototype.clone = function() {
          return new Request(this, {body: this._bodyInit})
        };
  
        function decode(body) {
          var form = new FormData();
          body
            .trim()
            .split('&')
            .forEach(function(bytes) {
              if (bytes) {
                var split = bytes.split('=');
                var name = split.shift().replace(/\+/g, ' ');
                var value = split.join('=').replace(/\+/g, ' ');
                form.append(decodeURIComponent(name), decodeURIComponent(value));
              }
            });
          return form
        }
  
        function parseHeaders(rawHeaders) {
          var headers = new Headers();
          // Replace instances of \r\n and \n followed by at least one space or horizontal tab with a space
          // https://tools.ietf.org/html/rfc7230#section-3.2
          var preProcessedHeaders = rawHeaders.replace(/\r?\n[\t ]+/g, ' ');
          preProcessedHeaders.split(/\r?\n/).forEach(function(line) {
            var parts = line.split(':');
            var key = parts.shift().trim();
            if (key) {
              var value = parts.join(':').trim();
              headers.append(key, value);
            }
          });
          return headers
        }
  
        Body.call(Request.prototype);
  
        function Response(bodyInit, options) {
          if (!options) {
            options = {};
          }
  
          this.type = 'default';
          this.status = options.status === undefined ? 200 : options.status;
          this.ok = this.status >= 200 && this.status < 300;
          this.statusText = 'statusText' in options ? options.statusText : 'OK';
          this.headers = new Headers(options.headers);
          this.url = options.url || '';
          this._initBody(bodyInit);
        }
  
        Body.call(Response.prototype);
  
        Response.prototype.clone = function() {
          return new Response(this._bodyInit, {
            status: this.status,
            statusText: this.statusText,
            headers: new Headers(this.headers),
            url: this.url
          })
        };
  
        Response.error = function() {
          var response = new Response(null, {status: 0, statusText: ''});
          response.type = 'error';
          return response
        };
  
        var redirectStatuses = [301, 302, 303, 307, 308];
  
        Response.redirect = function(url, status) {
          if (redirectStatuses.indexOf(status) === -1) {
            throw new RangeError('Invalid status code')
          }
  
          return new Response(null, {status: status, headers: {location: url}})
        };
  
        exports.DOMException = self.DOMException;
        try {
          new exports.DOMException();
        } catch (err) {
          exports.DOMException = function(message, name) {
            this.message = message;
            this.name = name;
            var error = Error(message);
            this.stack = error.stack;
          };
          exports.DOMException.prototype = Object.create(Error.prototype);
          exports.DOMException.prototype.constructor = exports.DOMException;
        }
  
        function fetch(input, init) {
          return new Promise(function(resolve, reject) {
            var request = new Request(input, init);
  
            if (request.signal && request.signal.aborted) {
              return reject(new exports.DOMException('Aborted', 'AbortError'))
            }
  
            var xhr = new XMLHttpRequest();
  
            function abortXhr() {
              xhr.abort();
            }
  
            xhr.onload = function() {
              var options = {
                status: xhr.status,
                statusText: xhr.statusText,
                headers: parseHeaders(xhr.getAllResponseHeaders() || '')
              };
              options.url = 'responseURL' in xhr ? xhr.responseURL : options.headers.get('X-Request-URL');
              var body = 'response' in xhr ? xhr.response : xhr.responseText;
              resolve(new Response(body, options));
            };
  
            xhr.onerror = function() {
              reject(new TypeError('Network request failed'));
            };
  
            xhr.ontimeout = function() {
              reject(new TypeError('Network request failed'));
            };
  
            xhr.onabort = function() {
              reject(new exports.DOMException('Aborted', 'AbortError'));
            };
  
            xhr.open(request.method, request.url, true);
  
            if (request.credentials === 'include') {
              xhr.withCredentials = true;
            } else if (request.credentials === 'omit') {
              xhr.withCredentials = false;
            }
  
            if ('responseType' in xhr && support.blob) {
              xhr.responseType = 'blob';
            }
  
            request.headers.forEach(function(value, name) {
              xhr.setRequestHeader(name, value);
            });
  
            if (request.signal) {
              request.signal.addEventListener('abort', abortXhr);
  
              xhr.onreadystatechange = function() {
                // DONE (success or failure)
                if (xhr.readyState === 4) {
                  request.signal.removeEventListener('abort', abortXhr);
                }
              };
            }
  
            xhr.send(typeof request._bodyInit === 'undefined' ? null : request._bodyInit);
          })
        }
  
        fetch.polyfill = true;
  
        if (!self.fetch) {
          self.fetch = fetch;
          self.Headers = Headers;
          self.Request = Request;
          self.Response = Response;
        }
  
        exports.Headers = Headers;
        exports.Request = Request;
        exports.Response = Response;
        exports.fetch = fetch;
  
        Object.defineProperty(exports, '__esModule', { value: true });
  
      })));
}