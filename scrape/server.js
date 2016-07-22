var coolibri = require('./coolibri.js');

var now = new Date();
var date = "";
date += (now.getDate() < 10 ? "0" : "") + now.getDate() + ".";
date += ((now.getMonth()+1) < 10 ? "0" : "") + (now.getMonth()+1) + ".";
date += (""+now.getFullYear()).substr(2,2);

coolibri.scrape_all("http://www.coolibri.de/veranstaltungen/" + date + ".html");
