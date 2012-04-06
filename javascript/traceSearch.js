if( document.getElementById("traceSearchArea") == null && document.getElementById("ibamain") != null ) {
    var ibamain = document.getElementById("ibamain");
    var main_div = document.createElement("div");
    main_div.id="traceSearchArea";
    ibamain.appendChild(main_div);
}

var traceSearch = function () {}

traceSearch.prototype = {
    traceShowChangeCookie : function() {
        //traceSearch,traceClose
        this.traceShow("traceSearchArea");
        this.traceShow("traceSearchPalette");
    },

    traceShow : function (id) {
        document.getElementById(id).style.display="block";
    },

    traceHide : function (id) {
        document.getElementById(id).style.display="none";
    },

    getSelectText : function () {
        var selectedText = "";
        if(navigator.userAgent.indexOf("MSIE",0) != -1){
            selectedObj=document.selection.createRange();
            selectedText=selectedObj.text;
        } else {
            selectedText='' + window.getSelection();
        }
        return selectedText;
    },

    isEffective : function (e) {
        var id = "";
        var cn = "";
        var tn = "";
        if (e) {
            if (e.target) {
                id = e.target.id;
                cn = e.target.className;
                tn = e.target.tagName;
            }
        } else if (window.event) {
            id = window.event.srcElement.id;
            cn = window.event.srcElement.className;
            tn = window.event.srcElement.tagName;
        }

        if (this.getTraceSearchAreaState() == "block") {
            if (id == "traceSearchArea" || id == "traceSearchWord" || id == "traceSearch") {
                return true;
            }
            if (tn == "ul" && cn != "traceList") {
                return true;
            }
            return false;
        }
        return false;
    },

    htmlspecialchars : function (str) {
        if (str != "") {
            str = str.replace("&",  "&amp;");
            str = str.replace(">",  "&gt;");
            str = str.replace("<",  "&lt;");
            str = str.replace("\"", "&quot;");
            str = str.replace("'",  "&#039;");
        }
        return str;
    },

    getTraceSearchAreaState : function (){
        return document.getElementById("traceSearchArea").style.display;
    },

    setPosition : function (e){ 
        if (navigator.userAgent.indexOf("MSIE",0) != -1) {
            selectedObj=document.selection.createRange();
            if (document.body.scrollTop) {
                document.getElementById("traceSearchArea").style.top=(document.body.scrollTop + selectedObj.offsetTop + 20);
                document.getElementById("traceSearchArea").style.left=(event.clientX);
            } else {
                document.getElementById("traceSearchArea").style.top=(document.documentElement.scrollTop + selectedObj.offsetTop + 20);
                document.getElementById("traceSearchArea").style.left=(event.clientX);
            }
        } else {
            var mX=this.getMousePosition(e).x;
            var mY=this.getMousePosition(e).y;
            document.getElementById("traceSearchArea").style.top=mY+10+'px';
            document.getElementById("traceSearchArea").style.left=mX+'px';
        }
    },

    getMousePosition : function (e) { 
        var obj = new Object(); 
        if (e) {
            obj.x = e.pageX; 
            obj.y = e.pageY; 
        } else { 
            obj.x = event.x + document.body.scrollLeft; 
            obj.y = event.y + document.body.scrollTop; 
        } 
        return obj; 
    },

    setTraceSearchCookie : function (key,val){
        tmp = key+"="+escape(val)+";";
        tmp += "expires=Fri, 31-Dec-2030 23:59:59;";
        document.cookie = tmp;
    },

    getTraceSearchCookie : function (key){
        tmp = document.cookie+";";
        tmp1 = tmp.indexOf(key,0);
        if(tmp1 != -1){
            tmp = tmp.substring(tmp1,tmp.length);
            start = tmp.indexOf("=",0);
            end = tmp.indexOf(";",start);
            return(unescape(tmp.substring(start+1,end)));
        }
        return("");
    },

    traceSearchResult : function (url) {
        if (document.getElementById("traceSearchWord")) {
            var searchtext = document.getElementById("traceSearchWord").value;
            if (!searchtext) {
                searchtext = document.getElementById("traceSearchWord").innerHTML;
            }
            url += encodeURI(searchtext);
            this.traceHide("traceSearchArea");
            var open_url = url;
            window.open(open_url);
        }
    },

    traceSearchMouseUp : function(e) {
        var obj = traceSearch.prototype;
        if (document.getElementById("traceSearchArea")) {
            var selectedText="";
            selectedText = obj.getSelectText();
            if (obj.isEffective(e)) {
                return;
            }
            if (selectedText.length > 0) {
                if (selectedText.length > 20) {
                    obj.traceHide("traceSearchArea");
                    return;
                }
                document.getElementById("traceSearchWord").value=obj.htmlspecialchars(selectedText);
                document.getElementById("traceSearchWord").innerHTML=obj.htmlspecialchars(selectedText);
                obj.setPosition(e);
                obj.traceShowChangeCookie();
                return;
            } else {
                if (obj.isEffective(e)) {
                    return;
                }
            }
            obj.traceHide("traceSearchArea");
            return;
        }
    },

    closure : function (n){
        return function(){traceSearch.prototype.traceSearchResult(traceSearchList[n]["url"]);};
    },

    run : function () {
        var d = document.createElement("div");
        d.id = "traceSearchPalette";d.name="traceSearchPalette";
        if (traceSearchVersion == "button") {
            var it = document.createElement("input");
            it.type="text";it.id="traceSearchWord";it.name="traceSearchWord";
            d.appendChild(it);
            for (var i = 0; i < traceSearchList.length; i++) {
                var bu = document.createElement("button");
                var f = this.closure(i);
                if (bu.addEventListener){
                    bu.addEventListener('click', f, true);
                } else if (bu.attachEvent){
                    bu.attachEvent('onclick', f);
                }
                bu.className = "traceList";
                bu.innerHTML = traceSearchList[i]["text"];
                d.appendChild(bu);
            }
        }
        if (traceSearchVersion == "link") {
            var di = document.createElement("span");
            di.id="traceSearchWord";di.name="traceSearchWord";
            d.appendChild(di);
            var sp = document.createElement("span");
            sp.id="traceSearchWordSufiix";sp.name="traceSearchWordSuffix";
            sp.innerHTML = "&#12434;";
            d.appendChild(sp);
            var ul = document.createElement("ul");
            ul.id="traceSearch";ul.name="traceSearch";
            d.appendChild(ul);
            for (var i = 0; i < traceSearchList.length; i++) {
                var li = document.createElement("li");
                var a = document.createElement("a");
                var f = this.closure(i);
                if (a.addEventListener){
                    a.addEventListener('click', f, true);
                } else if (a.attachEvent){
                    a.attachEvent('onclick', f);
                }
                a.innerHTML = traceSearchList[i]["text"];
                li.className = "traceList";
                li.appendChild(a);
                ul.appendChild(li);
            }
        }
        if (traceSearchVersion == "single") {
            var di = document.createElement("a");
            var f = this.closure(0);
            if (di.addEventListener){
                di.addEventListener('click', f, true);
            } else if (di.attachEvent){
                di.attachEvent('onclick', f);
            }
            di.id="traceSearch";di.name="traceSearch";
            di.innerHTML = "&#8811;&#12300;";
            d.appendChild(di);
            var sp = document.createElement("span");
            sp.id="traceSearchWord";sp.name="traceSearchWord";
            di.appendChild(sp);
            di.innerHTML += "&#12301;&#12434;" + traceSearchList[0]["text"];
        }
        var area = document.getElementById("traceSearchArea");
        area.appendChild(d);
        this.traceHide("traceSearchArea");

    }
};

var trace = new traceSearch;
trace.run();

if (window.addEventListener){
    window.addEventListener('mouseup', trace.traceSearchMouseUp, true);
} else if (document.body.attachEvent){
    document.body.attachEvent('onmouseup', trace.traceSearchMouseUp);
}
