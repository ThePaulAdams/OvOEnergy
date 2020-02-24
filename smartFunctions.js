function showOvoGraph()
{
	{
		$.post("./OvoEnergy.php",
		function (data)
		{
			console.log(data);
			var Etime = [];
			var Etemps = [];
			var Gtemps = [];

			for (var i in data[0]) {
				Etime.push(data[0][i].dateandtime);
				Etemps.push(data[0][i].consumption);
			}
			for (var i in data[1]) {
				Gtemps.push(data[1][i].consumption);
			}

			var chartdata = {
				labels: Etime,
				datasets: [
					{
						radius: 0,
						label: 'Electricity',
						borderColor: '#666666',
						hoverBackgroundColor: '#666666',
						hoverBorderColor: '#666666',
						fill: false,
						data: Etemps
					},
					{
						radius: 0,
						label: 'Gas',
						borderColor: '#cfb34e',
						hoverBackgroundColor: '#cfb34e',
						hoverBorderColor: '#cfb34e',
						fill: false,
						data: Gtemps
					}
				]
			};

			var graphTarget = $("#graphCanvasOvo");

			var barGraph = new Chart(graphTarget, {
				type: 'line',
				data: chartdata
			});
		});
	}
}