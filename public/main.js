$(function () {
	function showMovie(title) {
		$.get("/movie/" + encodeURIComponent(title),
			function (data) {
				if (!data) return;
				$("#title").text(data.title);
				$("#poster").attr("src", "http://neo4j-contrib.github.io/developer-resources/language-guides/assets/posters/" + encodeURIComponent(data.title) + ".jpg");
				var $list = $("#crew").empty();
				data.cast.forEach(function (cast) {
					$list.append($("<li>" + cast.name + " " + cast.job + (cast.job == "acted" ? " as " + cast.role : "") + "</li>"));
				});
			}, "json");
		return false;
	}

	function search() {
		var query = $("#search").find("input[name=search]").val();
		$.get("/search?q=" + encodeURIComponent(query),
			function (data) {
				var t = $("table#results tbody").empty();
				if (!data || data.length == 0) return;
				data.forEach(function (row) {
					var movie = row.movie;
					$("<tr><td class='movie'>" + movie.title + "</td><td>" + movie.released + "</td><td>" + movie.tagline + "</td></tr>").appendTo(t)
						.click(function () {
							showMovie($(this).find("td.movie").text());
						})
				});
				showMovie(data[0].movie.title);
			}, "json");
		return false;
	}

	$("#search").submit(search);
	search();
})

var width = 800,
	height = 800,
	force = d3.layout.force().charge(-200).linkDistance(30).size([width, height]);

var svg = d3.select("#graph").append("svg")
	.attr("width", "100%").attr("height", "100%")
	.attr("pointer-events", "all");

d3.json("/graph", function (error, graph) {
	if (error) return;

	force.nodes(graph.nodes).links(graph.links).start();

	var link = svg.selectAll(".link")
		.data(graph.links).enter()
		.append("line").attr("class", "link");

	var node = svg.selectAll(".node")
		.data(graph.nodes).enter()
		.append("circle")
		.attr("class", function (d) {
			return "node " + d.label
		})
		.attr("r", 10)
		.call(force.drag);

	// html title attribute
	node.append("title")
		.text(function (d) {
			return d.title;
		})

	// force feed algo ticks
	force.on("tick", function () {
		link.attr("x1", function (d) {
				return d.source.x;
			})
			.attr("y1", function (d) {
				return d.source.y;
			})
			.attr("x2", function (d) {
				return d.target.x;
			})
			.attr("y2", function (d) {
				return d.target.y;
			});

		node.attr("cx", function (d) {
				return d.x;
			})
			.attr("cy", function (d) {
				return d.y;
			});
	});
});
