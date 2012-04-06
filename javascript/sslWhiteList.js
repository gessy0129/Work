var GessyWhiteList = function() {}

GessyWhiteList.prototype = {
    getProtocol : function() {
        var protocol = "http://";
        var sslWhiteList = this.getSSLWhiteList();
        for (var i in sslWhiteList) {
            if (navigator.userAgent.match(sslWhiteList[i].regexp)) {
                protocol = "https://";
            }
        }
        return protocol;
    },
    getSSLWhiteList : function() {
        var sslWhiteList = [
           {
               os: 'iPhone',
               os_v: '3_0',
               browser: '4.0',
               regexp: 'iPhone.*?OS 3_0.*?Version/4.0',
           },
           {
               os: 'Android',
               os_v: '2.3',
               browser: '4.0',
               regexp: 'Android 2.3.*?Version/4.0',
           }
       ];
       return sslWhiteList;
   }
}
