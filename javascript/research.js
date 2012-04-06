var loadedSearchFrame;

function gessy0129ReSearch(media) {
    this.initialize.apply(this, arguments);
    this.media = media;
    document.write("<script type=\"text/javascript\" src=\"http://gessy0129.com/log/?media=" + media + "&product=3&locate=&design=\"></script>");
}

gessy0129ReSearch.prototype = {
    initialize: function(args) {
        this.has_query       = false;
        this.search_url      = "";
        this.search_link_url = ""; 
        this.iframe_style    = {};
        this.search_option   = {};
        this.flag            = false;
        this.d_top           = 0;
        this.d_left          = 0;
        this.d_right         = "0";
        this.m_top           = 0;
        this.m_left          = 0;
    },

    loadSearchedQuery : function( ) {
        var ref = this._getReferer();
        if(!ref) return;
        this.referrer = ref;
        var SearchEngineList = [
            {
                name: 'google',
                regexp: '^http://www\.google\.(co\.jp|com)',
                query: 'q',
                input_enc_key: 'ie',
                input_enc: '',
                type : 'site'
            },
            {
                name: 'yahoo',
                regexp: '^http://search\.yahoo\.(co\.jp|com)',
                query: 'p',
                input_enc_key: 'ei',
                input_enc: '',
                type : 'site'

            },
            {
                name: 'debug',
                regexp: '^http://gessy0129\.com',
                query: 'q',
                input_enc_key: '',
                input_enc: 'utf-8',
                type : 'site'

            },
            {
                name: 'msn',
                regexp: '^http://search\.(msn\.co\.jp|live\.com)/results\.aspx',
                query: 'q',
                input_enc_key: '',
                input_enc: 'utf-8',
                type : 'site'

            },
            {
                name: 'baidu',
                regexp: '^http://www\.baidu\.jp/s',
                query: 'wd',
                input_enc_key: 'ie',
                input_enc: '',
                type : 'site'
            },
            {
                name: 'goo',
                regexp: '^http://search\.goo\.ne\.jp/web\.jsp',
                query: 'MT',
                input_enc_key: 'IE',
                input_enc: 'utf-8',
                type : 'site'
            },
            {
                name: 'nifty',
                regexp: '^http://search\.nifty\.com/websearch/search',
                query: 'q',
                input_enc_key: '',
                input_enc: 'utf-8',
                type : 'site'
            },
            {
                name: 'infoseek',
                regexp: '^http://search\.www\.infoseek\.co\.jp/Web',
                query: 'qt',
                input_enc_key: '',
                input_enc: 'utf-8',
                type : 'site'
            },
            {
                name: 'fc2',
                regexp: '^http://blogsearch\.fc2\.com/',
                query: 't',
                input_enc_key: '',
                input_enc: 'utf-8',
                type : 'blog'
            },
            {
                name: 'baidu',
                regexp: '^http://blog\.baidu\.jp/s',
                query: 'wd',
                input_enc_key: '',
                input_enc: 'utf-8',
                type : 'blog'
            },
            {
                name: 'ask',
                regexp: '^http://ask\.jp/blog\.asp',
                query: 'q',
                input_enc_key: '',
                input_enc: 'utf-8',
                type : 'blog'
            },
            {
                name: 'nifty',
                regexp: '^http://search\.nifty\.com/blogsearch/search',
                query: 'q',
                input_enc_key: '',
                input_enc: 'utf-8',
                type : 'blog'
            },
            {
                name: 'google',
                regexp: '^http://blogsearch\.google\.co\.jp/blogsearch',
                query: 'q',
                input_enc_key: 'ie',
                input_enc: 'utf-8',
                type : 'blog'
            },
            {
                name: 'yahoo',
                regexp: '^http://blog-search\.yahoo.co\.jp/search',
                query: 'p',
                input_enc_key: 'ei',
                input_enc: 'utf-8',
                type : 'blog'
            }
        ];
        for(var i = 0; i < SearchEngineList.length; i++) {
            if( ref.match(SearchEngineList[i].regexp) ) {
                var params = this._parseQuery(ref);
                var query  = params[SearchEngineList[i].query];
                var input_enc = params[SearchEngineList[i].input_enc_key]
                          || SearchEngineList[i].input_enc
                          || 'utf-8';
                var engine_name = SearchEngineList[i].name;
                if (input_enc != 'utf-8' && input_enc != 'UTF-8') return; //utf-8 only
                this.query         = query;
                this.has_query     = true;
                this.escaped_query = this._escape(query); 
                this.input_enc     = input_enc;
                this.engine_name   = engine_name;
                this.search_link_url = this.search_url + encodeURIComponent(query);
            } 
        }
    },

    createSearchForm : function() {
        if ( !this.has_query ) return;
        if (!this.query || !this.input_enc || !this.engine_name ) return;
        var font_color = '#fff';
        var div = document.createElement("div");
        div.id = 'gessy0129_research_div';
        div.innerHTML = '<a href="javascript:void(0);" onclick="var search_frame = document.getElementById(\'gessy0129_research_div\'); search_frame.style.display = \'none\'"  class="close_btn"><img id="close_button" src="http://gessy0129.com/research/img/close.gif" width="12px" height="12px" /></a>';
        var iframe_id  = 'gessy0129_research_frame';
        var iframe = document.createElement("iframe");
        iframe.frameBorder           = 0;
        iframe.id                    = iframe_id;
        iframe.allowTransparency     = 'true';
        iframe.style.zIndex          =  2;
        iframe.style.height          = '88px';
        iframe.style.width           = '348px';
        iframe.style.margin          = 0;
        iframe.style.scrolling       = 'no';
        div.appendChild(iframe);
        document.getElementsByTagName("body")[0].appendChild(div);
        var iframedoc = document.all ? iframe.contentWindow.document : iframe.contentDocument;
        iframedoc.open();
        iframedoc.writeln('<?xml version="1.0" encoding="' + this.input_enc + '"?>'
                + '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
                + '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">'
                + '<head>'
                + '<meta http-equiv="Content-Type" content="text/html; charset=' + this.input_enc + '" />'
                + '<meta http-equiv="Content-Style-Type" content="text/css" />'
                + '<meta http-equiv="Content-Script-Type" content="text/javascript" />'
                + '<link rel="stylesheet" href="http://gessy0129.com/research/css/iframe.css" type="text/css">'
                + '</head>'
                + '<body>'
                + '<div class="searchFrame">'
                + '<input id="re_search" type="text" value="' + this.query + '" onkeydown="javascript:check(event, \'' +this.search_url + '\', \'' + this.media + '\');"/>'
                + '<img id="search_button" src="http://gessy0129.com/research/img/button.gif" width="67px" height="38px" onclick="javascript:research_action(\'' + this.search_url + '\', \'' + this.media + '\');" />'
                + '</div>'
                + '<script type="text/javascript" src="http://gessy0129.com/research/js/iframe.js"></script>'
                + '</body></html>' );
        iframedoc.close();
        var obj = this;
        this._addSetEvent(function(){obj.mouseup();}, function(){obj.mousemove();}, function(){obj.mousedown();});
    },

    drawSearchFrame : function() {
        if ( !loadedSearchFrame ) {
            loadedSearchFrame = 1;
            var obj = this;
            this._addLoadEvent( function() {
                obj.createSearchForm();
            });
        }
     },

     _escape : function(str){
         return str.replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
     },

    _parseQuery : function(str) {
        var params = new Object();
        if(typeof(str) == 'undefined') return params;
        if(str.indexOf('?', 0) > -1) str = str.split('?')[1];
        var pairs = str.split('&');
        for(var i = 0; i < pairs.length; i++){
            var pair = pairs[i].split("=");
            if(pair[0] != ''){
                var val = pair[1].replace(/\+/g, ' ');
                params[pair[0]] = decodeURIComponent(val);
            }
        }
        return params;
    },

    _getReferer : function() {
        if ( this.referrer ) {
            return this.referrer;
        }
        var d = document;
        var w = window;
        var ref = '';
        if(w.self == w.parent) {
            ref = d.referrer;
            if(!ref) return;
            if(d.parent && d.parent != undefined)
                ref = d.parent.referrer;
            if(ref.match(/^(undefined|unknown|bookmark)$/i))
                ref = '';
        }
        return ref;
    },

    mouseup : function(e) {
        this.flag = false;
        this.d_top = 0;
        this.d_right = 0;
        this.m_top = 0;
        this.d_left = 0;
        this.m_left = 0;
    },

    mousedown : function(e) {
        this.flag = false;
        this.d_top = 0;
        this.d_right = 0;
        this.m_top = 0;
        this.d_left = 0;
        this.m_left = 0;
        if (typeof window.event == 'object') {
            this.d_left = window.event.x;
            this.d_top = window.event.y;
        }
    },

    mousemove : function(e) {
        if (this.flag == true) {
            var div = document.getElementById('gessy0129_research_div');
            if (e) {
                var right = div.style.right;
                var top = div.style.top;
                if (this.d_right) {
                    top = (!top) ? 0 : parseInt(top);
                    right = (!right) ? 3 : parseInt(right);
                    var t = parseInt(e.pageY - this.d_top);
                    top = top + t;
                    t = parseInt(this.d_right - e.pageX);
                    right = right + t;
                }
                this.d_top = e.pageY;
                this.d_right = e.pageX;
                div.style.top = top + "px";
                div.style.right = right + "px";
            } else if (typeof window.event == 'object') {
                this.m_top = window.event.y;
                this.m_left = window.event.x;
                div.style.pixelTop += this.m_top - this.d_top;
                div.style.pixelLeft = (div.style.pixelLeft) ? div.style.pixelLeft : div.offsetLeft;
                div.style.pixelLeft += this.m_left - this.d_left;
                this.d_top = this.m_top;
                this.d_left = this.m_left;
            }
        }
    },

    _addSetEvent: function(mouseup, mousemove, mousedown) {
        var div = document.getElementById('gessy0129_research_div');
        var body = document.getElementsByTagName('body');
        if(typeof window.addEventListener == 'function'){
            div.addEventListener('mousedown', this.mousedown, false);
            div.addEventListener('mousemove', this.mousemove, false);
            div.addEventListener('mouseup', this.mouseup, false);
            return true;
        } else if(typeof window.attachEvent == 'object'){
            div.attachEvent('onmousedown', mousedown);
            div.attachEvent('onmousemove', mousemove);
            div.attachEvent('onmouseup', mouseup);
            return true;
        }
    },

    _addLoadEvent: function(func) {
        if(typeof window.addEventListener == 'function'){
            window.addEventListener('load', func, false);
            return true;
        } else if(typeof window.attachEvent == 'object'){
            window.attachEvent('onload', func);
            return true;
        }
        var oldonload = window.onload;
        if (typeof window.onload != 'function') {
            window.onload = func;
        } else {
            window.onload = function() {
                oldonload();
                func();
            };
        }
    }
}
