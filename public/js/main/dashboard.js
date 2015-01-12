var tmy_dashboard = {
	sales_charts: null,
	init : function() {
		this.initChart();
	},
	initChart: function(){
//		var d1 = [];
//		for (var i = 0; i < Math.PI * 2; i += 0.5) {
//			d1.push([i, Math.sin(i)]);
//		}
//	
//		var d2 = [];
//		for (var i = 0; i < Math.PI * 2; i += 0.5) {
//			d2.push([i, Math.cos(i)]);
//		}
		this.sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
		$.plot("#sales-charts", [
			{ label: "商品销售", data: dashboard_last30_data.sale },
			{ label: "会员光临", data: dashboard_last30_data.member }
		], {
			hoverable: true,
			shadowSize: 0,
			series: {
				lines: { show: true },
				points: { show: true }
			},
//			xaxis: {
//				 mode: "time",
//				 timeformat: "%m/%d"
//			},
//			yaxis: {
//				ticks: 10,
//				min: -2,
//				max: 2,
//				tickDecimals: 3
//			},
			grid: {
				backgroundColor: { colors: [ "#fff", "#fff" ] },
				borderWidth: 1,
				borderColor:'#555'
			}
		});
	}


};

$(function() {
	tmy_dashboard.init();
});
