var Coolibri = function () {}
var cheerio = require('cheerio')
var request = require('request')
var async = require("async")
var fs = require('fs');


Coolibri.prototype.scrape_event_page = function(html, url) {
  var data = {
    'location': {
      'lat': 0,
      'lon': 0,
      'street': '',
      'plz': 0,
      'city': '',
      'country': ''
    },
    'name': '',
    'description': '',
    'category': '',
    'time_start': 0,
    'time_end': 0,
    'photo': [],
    'website': ''
  }
  var $ = cheerio.load(html);
  data['name'] = $('.details > h1.d > span.name').text()
  data['description'] = $('.details > p.description').text()
  data['category'] = $('.details > .what > p.d').text().trim()

  data['time_start'] = $('meta[property="og:start_time"]').attr('content')
  data['time_start'] = $('meta[property="og:start_time"]').attr('content')
  // data['time_start'] = $('.when .d time[itemprop=startDate]').attr('datetime')
  // data['time_end'] = $('.when .d time[itemprop=endDate]').attr('datetime')
  data['website'] = url
  data['location']['lat'] = $('.details > span meta[itemprop=latitude]').attr('content')
  data['location']['lon'] = $('.details > span meta[itemprop=longitude]').attr('content')
  data['location']['plz'] = $('.address > div > span[itemprop=postalCode]').text()
  data['location']['city'] = $('.address > div > span[itemprop=addressLocality]').text()
  data['location']['street'] = $('.address > div[itemprop=streetAddress]').text()
  data['location']['country'] = 'DE'
  return data
}
Coolibri.prototype.list_events = function (html, url) {
  var $ = cheerio.load(html)
  var regex = /<a href="([^"]*)"[^>]*class="ts/g
  var event_list = []
  var match;
  while ((match = regex.exec(html)) !==null) {
    event_list.push("http://www.coolibri.de" + match[1])
  }
  var next = $('.b.forward >a').attr("href")
  if(next == undefined )
    console.error();
  var ret = {
    "events": event_list,
    "nextpage": next == undefined ? undefined : "http://www.coolibri.de" + next
  }
  return ret;
}
Coolibri.prototype.scrape_all = function (first_url)  {
  var data = []
  var event_urls = []
  var current_url = first_url
  async.series([
    function (callback) {
      console.log("Starting crawling events");
      async.whilst(
        function () { return current_url !== undefined},
        function (callback_w) {
          request(current_url, function (error, response, body) {
            console.log("GETting: " + current_url);
            if (!error && response.statusCode == 200) {
              var ev = Coolibri.prototype.list_events(body, current_url)
              event_urls = event_urls.concat(ev['events'])
              console.log(ev["nextpage"]);
              current_url = ev["nextpage"]
              callback_w()
            }
          })
        },
        function (err, n) {
          console.log("Finished crawling events");
          callback();
        }
      )
    },
    function (callback_s) {
      console.log("scraping " + event_urls.length + " URLs");
      var counter = 0
      async.mapSeries(event_urls,
        function (url, callback) {
          request(url, function (error, response, body) {
            if (error || response.statusCode != 200) {
              console.error(error);
              callback()
            }else {
              if(body == undefined)
                console.error("Body:" + body + " URL " + url + "----------------");
              var event_data = Coolibri.prototype.scrape_event_page(body, url)
              data.push(event_data)
              counter++;
              console.log("Finished " + counter + " of " + event_urls.length);
              callback()
            }
          })
        },
        function (err) {
          fs.writeFile("data/events.json", JSON.stringify(data), function(err) {
              if(err) {
                  return console.log(err);
              }
              console.log("The file was saved!");
              callback_s()
          });
        }
      )
    }
  ])
}
module.exports = new Coolibri()
