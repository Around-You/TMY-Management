var tmy_dashboard = {
	sales_charts: null,
	init : function() {
		this.initChart();
	},
	initChart: function(){
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
			xaxis: {
				ticks: dashboard_last30_data.x
			},
			yaxis: {
				minTickSize: 1,
				tickDecimals: 0
			},
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
