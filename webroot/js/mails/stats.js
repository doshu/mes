$(function() {

	var browserStatsData = {labels:[], dataset:[]};
	if(browserStatsDataset.length) {
		for(var i = 0, n = browserStatsDataset.length; i < n; i++) {
			browserStatsData.labels.push(browserStatsDataset[i].browser);
			browserStatsData.dataset.push(parseInt(browserStatsDataset[i].times));
		}
		var bsr = Raphael('browserStats', 250, 250);
		bsr.piechart(90, 125, 80, browserStatsData.dataset, {legend: browserStatsData.labels});
	}
	
	var deviceStatsData = {labels:[], dataset:[]};
	if(deviceStatsDataset.length) {
		for(var i = 0, n = deviceStatsDataset.length; i < n; i++) {
			deviceStatsData.labels.push(deviceStatsDataset[i].device);
			deviceStatsData.dataset.push(parseInt(deviceStatsDataset[i].times));
		}
		var dsr = Raphael('deviceStats', 250, 250);
		dsr.piechart(90, 125, 80, deviceStatsData.dataset, {legend: deviceStatsData.labels});
	}
	
	var osStatsData = {labels:[], dataset:[]};
	if(osStatsDataset.length) {
		for(var i = 0, n = osStatsDataset.length; i < n; i++) {
			osStatsData.labels.push(osStatsDataset[i].os);
			osStatsData.dataset.push(parseInt(osStatsDataset[i].times));
		}
		var osr = Raphael('osStats', 250, 250);
		osr.piechart(90, 125, 80, osStatsData.dataset, {legend: osStatsData.labels});
	}
	
	
	
	
	
	$('#geoChart').vectorMap({
		map: 'world_mill_en',
		scaleColors: ['#C8EEFF', '#0071A4'],
		normalizeFunction: 'polynomial',
		hoverOpacity: 0.7,
		hoverColor: false,
		markerStyle: {
		  initial: {
		    fill: '#FF2222',
		    stroke: '#383f47'
		  }
		},
		backgroundColor: '#383f47',
		markers: geoDataset,
		zoomMax: 100
	  });
  	
	
});
