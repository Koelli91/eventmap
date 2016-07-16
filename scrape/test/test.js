//var assert = require('chai').assert
var Coolibri = require('../coolibri.js')
var fs = require('fs')
var assert = require('assert')

describe('Scrape single event page', function () {

  var sample_html = fs.readFileSync('event_single.html')
  var sample_data = {
    'location': {
      'lat': "51.42108",
      'lon': "7.57947",
      'street': "Bürenbrucherweg 19",
      'plz': "58239",
      'city': 'Schwerte',
      'country': 'DE'
    },
    'name': "Adam Bauer & The Love Keys",
    'description': "Meditationsmusik",
    'category': 'Konzerte',
    'time_start': "2016-07-16T17:00:00+0200",
    'time_end': "",
    'photo': [],
    'website': "http://www.coolibri.de/veranstaltungen/heute/adam-bauer-the-love-keys--/1669214.html"
  }

  it('should return the right data', function(){
    assert.deepEqual(Coolibri.scrape_event_page(sample_html, 'http://www.coolibri.de/veranstaltungen/heute/adam-bauer-the-love-keys--/1669214.html'),sample_data)
  });
})
describe('Scrape Event List', function () {

    var sample_html = fs.readFileSync('event_list.html')
    var sample_data = "www.coolibri.de/veranstaltungen/18.07.16/achim-bardohl-trio--/1675104.html"
    it('should get all links', function (done) {
      events_data = Coolibri.list_events(sample_html)
      assert.equal(events_data["events"][0], sample_data)
      assert.equal(events_data["events"][events_data["events"].length -1], "www.coolibri.de/veranstaltungen/18.07.16/klaus-brandner-viva-la-viva--/1611620.html")
      assert.equal(events_data["nextpage"], "www.coolibri.de/veranstaltungen/18.07.16/2.html")
      done()
    })
})
