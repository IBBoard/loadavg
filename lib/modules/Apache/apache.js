if (typeof charts == "undefined") {
	var charts = {};
}

charts.apache_usage = 
{
	// chart data
	data: 
	{
		d1: []
	},

	// will hold the chart object
	plot: null,

	$data: null,

	// chart options
	options: 
	{
		grid: {
			show: true,
		    aboveData: true,
		    color: "#3f3f3f",
		    labelMargin: 5,
		    axisMargin: 0, 
		    borderWidth: 1,
		    borderColor: null,
		    minBorderMargin: 5 ,
		    clickable: true, 
		    hoverable: true,
		    autoHighlight: true,
		    mouseActiveRadius: 20,
		    backgroundColor : { }
		},
        series: {
        	grow: {active:false},
            lines: {
        		show: true,
        		fill: true,
        		lineWidth: false,  //set to false here for faded chart peaks or 2 for not
        		steps: false
        	},
            points: {show:false}
        },
        legend: { position: "ne", backgroundColor: "#000", backgroundOpacity: .4 },
        yaxis: {
		labelWidth: 40,
        	tickDecimals:1,
        	tickFormatter: function(val,axis){
		         return parseFloat(val).toFixed(axis.tickDecimals);
		      }
        },
        xaxis: {mode: 'time', minTickSize: ["1", "hour"], timeformat: "%H", min: today_min, max: today_max, ticks: 12},
        // xaxis: {mode: 'time', timezone: "browser", minTickSize: [10, "minute"]},
        colors: [],
        shadowSize:1,
        tooltip: true,

		tooltipOpts: {
			content: function(label, xval, yval, flotItem){

				return "Load:" + yval;

			   	//return "%s : %y ";
				/*
			        var yAxis = plot.getYAxes();
			        var range = (yAxis.max - yAxis.min);

			        if (range > 0.01) {
			          // range is larger than a year, just show year
			          return $.plot.formatDate(new Date(yval), '%Y');
			        }
			    */
	    	},

			shifts: {
				x: 10,
				y: 20
			},
			precision: 2,
			dateFormat: "%y-%0m-%0d",
			defaultTheme: false
		}
	},

	setData: function($data)
	{
		if ($.isArray($data))
		{
			this.options.yaxis.min = $data[0].ymin;
			this.options.yaxis.max = $data[0].ymax;
			this.$data = $data;
		}
		else {
			this.data.d1 = $data.data;
			this.options.yaxis.min = $data.ymin;
			this.options.yaxis.max = $data.ymax;
			this.label = $data.label;
		}
	},

	// initialize
	init: function($divId)
	{
		// apply styling
		this.utility.applyStyle(this);
		this.options.legend.container = "#"+$divId+"_legend";
		this.options.legend.noColumns = 3;
		
		var p = $('#' + $divId);
		p.width('530');
		
		// make chart
		if ($.isArray(this.$data))
		{
			this.plot = $.plot(
				'#' + $divId, 
				[{
	     			label: this.$data[0].label, 
	     			data: this.$data[0].data,
	     			lines: {fillColor: "#8ec657"},
	     			points: {fillColor: "#88bbc8"},
	     			color: "#8ec657"
	     		},{
	     			label: this.$data[1].label, 
	     			data: this.$data[1].data,
	     			lines: {show: false},
	     			bars: {
	                    show: true,
	                    barWidth: 1,
	        			fill: 1,
	        			fillColor: "#ebc824"
	                },
	        		color: "#ebc824"
	     		},{
	     			label: this.$data[2].label, 
	     			data: this.$data[2].data,
	     			lines: {show: true, fill: false, fillColor: "#88bbc8"},
	     			points: { fill: false, fillColor: "#88bbc8" },
	     			color: "#c65757"
	     		}], 
	     		this.options);
		}
		else
		{
			this.plot = $.plot(
				'#' + $divId, 
				[{
	     			label: this.label, 
	     			data: this.data.d1,
	     			lines: {fillColor: "#8ec657"},
	     			points: {fillColor: "#88bbc8"},
	     			color: "#8ec657"
	     		}], 
	     		this.options);
		}
	},

	utility:
	{
		chartColors: [ "#797979", "#444", "#777", "#999", "#797979", "#EEE" ],
		chartBackgroundColors: ["#353535", "#353535"],

		applyStyle: function(that)
		{
			that.options.colors = that.utility.chartColors;
			that.options.grid.backgroundColor = { colors: that.utility.chartBackgroundColors };
			that.options.grid.borderColor = that.utility.chartColors[4];
			that.options.grid.color = that.utility.chartColors[5];
		}
	}
}
