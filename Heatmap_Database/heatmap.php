<!DOCTYPE html>
<html>
<head>
	<title>Drug Heatmap</title>
	<script type="text/javascript" src="http://d3js.org/d3.v3.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</head>
<body>
	<?php
		// get information from the <form> in html
		$disname = filter_input(INPUT_GET, "diseasename");
		// connect to database and select the database
		$conn = mysql_connect("localhost") or die('Unable to connect to MySQL.'.mysql_error());
		$select = mysql_select_db("heatmap") or die('Unable to select database.'.mysql_error());
		// get info of genes, saved in gene
		$gene2disease = "SELECT genename, geneweight, genexp FROM gene2disease WHERE disname = '$disname'";
		$genequery = mysql_query($gene2disease, $conn) or die('Unable to query.'.mysql_error());
		$gene = array();
		while($row = mysql_fetch_assoc($genequery)) {$gene[] = $row;}
		// get names of drugs, saved in drug
		$drug2disease = "SELECT drugname, drugtype FROM drug2disease WHERE disname = '$disname' ORDER BY drugtype";
		$drugquery = mysql_query($drug2disease, $conn) or die ('Unable to query.'.mysql_error());
		$drug = array();
		while($row = mysql_fetch_assoc($drugquery)) {$drug[] = $row;}
		// get drugs PETscores
		$score = array();
		for ($i = 0; $i < count($drug); $i++) {
			$drugeach = array();
			$druglabel = $drug[$i]['drugname'];
			for ($j = 0; $j < count($gene); $j++) {
				$genelabel = $gene[$j]['genename'];
				$drug2gene = "SELECT genescore FROM drug2gene WHERE drugname = '$druglabel' AND genename = '$genelabel'";
				$scorequery = mysql_query($drug2gene, $conn) or die ('Unable to query.'.mysql_error());
				$drugeach[] = mysql_fetch_assoc($scorequery);
			}
			$score[] = $drugeach;
		}
		$dataset = array();
		$dataset[0] = $gene;
		$dataset[1] = $drug;
		$dataset[2] = $score;
		// send all data to javascript
		$DataToJava = json_encode($dataset);
	?>
	<script type="text/javascript">
		// get all data from php, the rest part run on local machine
		var alldata = <?php echo $DataToJava ?>;
		console.log(alldata);
		// set original x coordinate
		var genex =[0];
		for (var i = 0; i < alldata[0].length; i++) {
			genex.push(genex[i] + parseFloat(alldata[0][i]["geneweight"]));
		}
		// extract all gene and drug scores in an array, get all drugscore to calculate
		var scorearray = [];
		var drugscore = [];
		for (var i = 0; i < alldata[1].length + 1; i++) {
			var row = [];
			for (var j = 0; j < alldata[0].length; j++) {
				if (i == 0) {
					row.push(parseFloat(alldata[0][j]["genexp"]));
				}else{
					row.push(parseFloat(alldata[2][i - 1][j]["genescore"]));
					drugscore.push(parseFloat(alldata[2][i - 1][j]["genescore"]));
				}
			}
			scorearray.push(row);
		}
		console.log(scorearray);
		// One functions to calculate STD and get standard deviation
		function stdDev(array) {
			function Sum(array) {
			    var sum = array.reduce(function(sum, value){
			        return sum + value;
			    }, 0);
			    return sum;
			}
			function average(data) {
			    var avg = Sum(data) / data.length;
			    return avg;
			}
		    var avg = average(array);
		    var squareDiffs = array.map(function(value){
		        var diff = value - avg;
		        var sqr = diff * diff;
		        return sqr;
		    })
		    var avgSquareDiff = average(squareDiffs);
		    return Math.sqrt(avgSquareDiff);
		}
		var SD = stdDev(drugscore);
		// One function to get original coordinates for genelabel
		function origXY (x, y) {return [-y, x];}
		// find maxium pixel for drug name & gene name to set padding on the left and above
		var maxPixelD = 0; var maxPixelG = 0;
		d3.select("body")
			.append("svg")
			.attr("id", "temp")
			.attr("width", w)
			.attr("height", h)
				.selectAll("text")
				.data(alldata[1])
				.enter()
				.append("text")
				.text(function(d,i) {return d["drugname"]})
				.each(function() {
					if (maxPixelD < $(this).width()) {
						maxPixelD = $(this).width();
					}
				})
		$("#temp").remove();
		d3.select("body")
			.append("svg")
			.attr("id", "temp")
			.attr("width", w)
			.attr("height", h)
				.selectAll("text")
				.data(alldata[0])
				.enter()
				.append("text")
				.text(function(d,i) {return d["genename"]})
				.each(function() {
					if (maxPixelG < $(this).width()) {
						maxPixelG = $(this).width();
					}
				})
		$("#temp").remove();
		// set parameters for SVG
		var w = 900;
		var h = 600;
		var wType = 50;
		var paddingW = maxPixelD + 5 + wType;
		var paddingE = 10;
		var paddingN = maxPixelG + 8;
		var paddingS = 10;
		// create scale functions for width and height
		var xScale = d3.scale.linear()
					.range([paddingW, w - paddingE])
					.domain([0, genex[alldata[0].length]]);
		var yScale = d3.scale.ordinal()
					.domain(d3.range(alldata[1].length + 2))
					.rangeRoundBands([paddingN, h - paddingS]);
		// create scale variables and function for choosing colors
		function colorpicker (d, SD){
			var redto2sigma = d3.scale.linear()
				    .range([d3.rgb(255, 0, 0), d3.rgb(255, 85, 85)]);
		    var sigma2tosigma = d3.scale.linear()
				    .range([d3.rgb(255, 85, 85), d3.rgb(255, 170, 170)]);
		    var sigmatowhite = d3.scale.linear()
				    .range([d3.rgb(255, 170, 170), d3.rgb(255, 255, 255)]);
		    var whitetosigma = d3.scale.linear()
				    .range([d3.rgb(255, 255, 255), d3.rgb(170, 255, 170)]);
		    var sigmatosigma2 = d3.scale.linear()
				    .range([d3.rgb(170, 255, 170), d3.rgb(85, 255, 85)]);
		    var sigma2togreen = d3.scale.linear()
				    .range([d3.rgb(85, 255, 85), d3.rgb(0, 255, 0)]);
			if (d < - 2 * SD) {return redto2sigma(d);}
            else if (d >= - 2 * SD && d < - SD) {return sigma2tosigma(d);}
            else if (d >= - SD && d < 0) {return sigmatowhite(d);}
            else if (d == 0) {return "white";}
            else if (d > 0 && d <= SD) {return whitetosigma(d);}
            else if (d > SD && d <= 2 * SD) {return sigmatosigma2(d);}
            else if (d > 2 * SD) {return sigma2togreen(d);}   
		}             
	    var genecolor = d3.scale.ordinal()
			    .domain([-1, 0, 1])
			    .range(["yellow", "white", "blue"]);
		// draw rectangle based on score of genes or drugs
		var rowIndex = 0;
		d3.select("body")
			.append("svg")
			.attr("width", w)
			.attr("height", h)
				.selectAll("g")
				.data(scorearray)
				.enter()
				.append("g")
				.each(function(d,i){
					// draw each rectangle based on the score for each gene and drug
					rowIndex = i;
					d3.select(this)
					.selectAll("rect")
					.data(function(d) {return d;})
					.enter()
					.append("rect")
					.attr("x", function(d,i) {return xScale(genex[i])})
					.attr("y", function() {if (i == 0) {return yScale(i);} else {return yScale(i + 1);}})
					.attr("width", function(d,i) {return xScale(parseFloat(alldata[0][i]["geneweight"])) - paddingW;})
					.attr("height", yScale.rangeBand())
					.attr("fill", function(d,i) {
						if (rowIndex == 0) {return genecolor(d);}
						else {return colorpicker(d, SD);}
					})
					.attr("stroke", "black")
					.attr("stroke-width", 2);
					//draw the rectangle for each drug
					if (i != 0){
						d3.select(this)
						.append("rect")
						.attr("x", 0)
						.attr("y",yScale(i + 1))
						.attr("width", wType)
						.attr("height", yScale.rangeBand())
						.attr("fill", function() {
							var sum = 0;
							for (j = 0; j < alldata[0].length; j ++) {
								sum += parseFloat(alldata[0][j]["geneweight"]) * parseFloat(alldata[2][i - 1][j]["genescore"]);
							}
							var scoreD = sum / genex[alldata[0].length];
							return colorpicker(scoreD, SD);
						})
						.attr("stroke", "black")
						.attr("stroke-width", 2);
					}
					// type the drug names and gene names
					var label = [];
					if (i == 0) {
						// the length of label is different
						label = ["DRUG NAME", "TYPE"];
						// gene name starts with i == 2;
						for (var j = 0; j < alldata[0].length; j++) {
							label.push(alldata[0][j]["genename"]);
						}
					}else {label[0] = alldata[1][i - 1]["drugname"]; label[1] = alldata[1][i - 1]["drugtype"];}
					d3.select(this)
					.selectAll("text")
					.data(label)
					.enter()
					.append("text")
					.attr("x", function(d,i) {
						if (i == 0) {return (paddingW - wType) / 2 + wType;}
						else if (i == 1) {return wType / 2;}
						else {return 6 - paddingN;}
					})
					.attr("y", function(d,i){
						if (rowIndex == 0 && i < 2) {
							return yScale(rowIndex) + yScale.rangeBand() / 2;
						}else if (rowIndex == 0 && i > 1) { return xScale(genex[i - 1]) - (xScale(parseFloat(alldata[0][i - 2]["geneweight"])) - paddingW) / 2;
						}else {return yScale(rowIndex + 1) + yScale.rangeBand() / 2;}
					})
					.attr("transform", function(d,i) {
						if (rowIndex == 0 && i > 1) {return "rotate(-90)";} else {return "";}
					})
					.attr("font-size", yScale.rangeBand() / 1.5 + "px")
					.attr("fill", "black")
					.attr("text-anchor", function(d,i) {
						if (rowIndex == 0 && i > 1) {return "";} else {return "middle";}
					})
					.attr("dy", ".35em")
					.attr("font-weight", function() {if (i == 0) {return "bold";} else {return "";}})
					.text(function(d) {return d;})
				}) 
	</script>
</body>
</html>