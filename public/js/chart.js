 var options = {
          series: [{
          name: 'series2',
          data: [11, 32, 45, 32, 34, 52, 41],
		  
		  
        }],
		
          chart: {
			   background: '#fff',
          height: 350,
          type: 'area',
		
		  toolbar: {
        show: false,
        
     
       
        
      },
        },
		
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
		  
        },
		
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        };

        var chart = new ApexCharts(document.querySelector("#line-chart"), options);
        chart.render();