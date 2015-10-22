var spreadsheetGene;
var spreadsheetDrug;
var objDataGene;
var objDataDrug;
var arrKeysGene;
var arrKeysDrug;
var genenumber;
var drugnumber;

_parseFloat = function(string) {
	if (isNaN(string) === false) {
		if (string == '') {
			return 0;
		}else{
			return parseFloat(string);
		}
	}else {
		return string;
	}
}

var fGetKeys = function(obj){
	var keys = [];
	for(var key in obj){
	    keys.push(key);
	}
	return keys;
}

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

function stdDev(array) {
    var avg = average(array);
    var squareDiffs = array.map(function(value){
        var diff = value - avg;
        var sqr = diff * diff;
        return sqr;
    })
    var avgSquareDiff = average(squareDiffs);
    return Math.sqrt(avgSquareDiff);
}

function displacement(x, y) {
    var returnArray = [];
    var edge = Math.sqrt(Math.pow(x,2) + Math.pow(y,2));
    var sin = y / edge;
    var cos = x / edge;
    returnArray.push((cos - sin) * edge);
    returnArray.push((cos + sin) * edge);
    return returnArray;
}

function heatmap(datasetG, datasetD) {
	//Width, height and padding
	var w = 900;
    var hG = 100;
    var hD = 500;
    var paddingW = 170;
    var paddingE = 30;
    var wType = 40;
    var wLegend = 20;
    //Create scale functions for color
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
    var genecolor = d3.scale.ordinal()
		    .domain([-1, 0, 1])
		    .range(["yellow", "white", "blue"]);
    //Create scale functions. Set the domain after loading the data.
    var xScale = d3.scale.linear()
				.range([paddingW, w - paddingE]);
    var yScale = d3.scale.ordinal()
    			.domain(d3.range(drugnumber))
				.rangeRoundBands([0, hD]);
	//Create SVG element
    d3.select("#DrugType").append("svg")
                .attr("id", "SvgDrugType")
                .attr("width", wType)
                .attr("height", hD + hG)

    d3.select("#legend").append("svg")
                .attr("id", "SvgLegend")
                .attr("width", wLegend)
                .attr("height", hD + hG)

    var svgG = d3.select("body")
				.append("svg")
				.attr("width", w)
				.attr("height", hG);
	var svgD = d3.select("body")
				.append("svg")
				.attr("width", w)
				.attr("height", hD);
	//Local variants
    var geneName = [];
    var genesize = [];
    var genex = [0];
    var maxPixel = 0;
    var alldata = [];
    var drugType = [];
    var typeSplit = [0];
    var SD = 0;
    var n = 0;
    var geneTextY = [];
    var legendNumber = ["1", "0", "-1"];
    // Start drawing
    svgG.append("g")
    	.attr("id", "Gene")
    	.selectAll("rect")
    	.data(datasetG)
    	.enter()
    		.append("rect")
    		.each(function(d,i) {
                geneName.push(d[arrKeysGene[0]]);
    			genesize.push(parseFloat(d[arrKeysGene[1]]));
    			if (i == genenumber - 1) {
    				for (var n = 0, t = 0; n < genesize.length - 1; n ++){
                        genex.push(genesize[n] + genex[t]);
                        t ++;
                    }
                    xScale.domain([0, genex[genenumber - 1] + genesize[genenumber - 1]]);
    			}
    		})
    		.attr("x", function(d,i) {
    			return xScale(genex[i]);
    		})
    		.attr("y", hG - yScale.rangeBand() - 10)
    		.attr("width", function(d,i) {
    			return xScale(genesize[i]) - paddingW;
    		})
    		.attr("height", yScale.rangeBand())
    		.attr("fill", function(d) {
    			return genecolor(d[arrKeysGene[2]]);
    		})
    		.attr("stroke", "black")
    		.attr("stroke-width", 2);

    svgD.selectAll("g")
        .attr("id", "Drug")
    	.data(datasetD)
    	.enter()
    		.append("g")
    		.each(function(d,i) {
    			for (var j = 2; j < arrKeysDrug.length; j++) {
    				alldata.push(parseFloat(d[arrKeysDrug[j]]));
    			}
                if (i == 0) {
                    drugType.push(d[arrKeysDrug[1]]);
                }else {
                    if(drugType.indexOf(d[arrKeysDrug[1]]) == -1) {
                        drugType.push(d[arrKeysDrug[1]]);
                        typeSplit.push(i);
                    }
                    if (i == drugnumber - 1) {
                        typeSplit.push(i);
                    }
                }
    			d3.select(this)
    				.append("text")
    				.attr("y", yScale(i) + yScale.rangeBand()/1.5)
    				.attr("font-family", "sans-serif")
		    		.attr("font-size", yScale.rangeBand()/1.5 + "px")
		    		.attr("fill", "black")
		    		.attr("text-anchor", "middle")
    				.text(d[arrKeysDrug[0]])
    				.each(function() {
					 	if(maxPixel < $(this).width()) {
					 		maxPixel = $(this).width()
					 	}
					 	if (i == drugnumber - 1) {
					 		paddingW = maxPixel + 30;
					 		xScale.range([paddingW, w - paddingE]);
					 		$("text").attr("x", paddingW / 2);
					 	}
    				})
    			if (i == drugnumber - 1) {
    				SD = stdDev(alldata);
    				redto2sigma.domain([-1, - SD * 2]);
                    sigma2tosigma.domain([- SD * 2, - SD]);
                    sigmatowhite.domain([-SD, 0]);
                    whitetosigma.domain([0, SD]);
                    sigmatosigma2.domain([SD, 2 * SD]);
                    sigma2togreen.domain([2 * SD, 1]);
                    var geneReset = $("#Gene").find("rect");
			 		for(var i = 0; i < genenumber; i++){
			 			$(geneReset[i])
			 			.attr("x", xScale(genex[i]))
			 			.attr("width", xScale(genesize[i]) - paddingW);
    				}
    			}
    		})
			.selectAll("rect")
    		.data(function(d) {
    			var valofDrug = [];
    			for (var j = 2; j < arrKeysDrug.length; j++) {
    				valofDrug.push(parseFloat(d[arrKeysDrug[j]]));
    			}
    			return valofDrug;
    		})
    		.enter()
    			.append("rect")
    			.attr("x", function(d,i) {
    				return xScale(genex[i]);
    			})
    			.attr("y", function(d,i) {
    				if (i == genesize.length - 1) {
                        n ++;
                        return yScale(n - 1);
                    }else{
                    	return yScale(n);
                    }
    			})
    			.attr("width", function(d,i) {
    				return xScale(genesize[i]) - paddingW;
    			})
    			.attr("height", yScale.rangeBand())
    			.attr("fill", function(d) {
	    			if (d < - 2 * SD) {return redto2sigma(d);}
                    else if (d >= - 2 * SD && d < - SD) {return sigma2tosigma(d);}
                    else if (d >= - SD && d < 0) {return sigmatowhite(d);}
                    else if (d == 0) {return "white";}
                    else if (d > 0 && d <= SD) {return whitetosigma(d);}
                    else if (d > SD && d <= 2 * SD) {return sigmatosigma2(d);}
                    else if (d > 2 * SD) {return sigma2togreen(d);}
	    		})
	    		.attr("stroke", "black")
	    		.attr("stroke-width", 2);

    svgG.append("g")
        .attr("id", "GeneName")
        .selectAll("text")
        .data(geneName)
        .enter()
            .append("text")
            .each(function(d,i) {
                if (i == 0) {
                    geneTextY.push(hG - yScale.rangeBand() - 10 - 15);
                }else{
                    geneTextY.push(geneTextY[i - 1] + (xScale(genesize[i - 1]) - paddingW) / 2 + (xScale(genesize[i]) - paddingW) / 2);
                }
                if (i == genenumber -1) {
                    var trans = displacement(xScale(genex[0]) + (xScale(genesize[0]) - paddingW) / 2, hG - yScale.rangeBand() - 10 - 15);
                    $("#GeneName").attr("transform", "translate(" + trans[0] + "," + trans[1] + ")");
                }
            })
            .attr("x", xScale(genex[0]) + (xScale(genesize[0]) - paddingW) / 2)
            .attr("y", function(d,i) {
                return geneTextY[i];
            })
            .attr("dy", ".35em")
            .attr("font-family", "sans-serif")
            .attr("font-size", yScale.rangeBand()/1.5 + "px")
            .attr("fill", "black")
            .attr("transform", "rotate(-90)")
            .text(function(d) {
                return d;
            })

    d3.select("#SvgDrugType")
        .selectAll("rect")
        .data(drugType)
        .enter()
            .append("rect")
            .attr("x", 0)
            .attr("y", function(d,i) {
                return yScale(typeSplit[i]) + hG;
            })
            .attr("width", wType)
            .attr("height", function(d,i) {
                if (i == drugType.length - 1){
                    return yScale(typeSplit[i + 1]) - yScale(typeSplit[i]) + yScale.rangeBand();
                }else {
                    return yScale(typeSplit[i + 1]) - yScale(typeSplit[i]);
                }
            })
            .attr("fill", function(d,i) {
                var subarray = [];
                if (i == drugType.length - 1) {
                    subarray = alldata.slice(typeSplit[i] * 10, alldata.length);
                }else{
                    subarray = alldata.slice(typeSplit[i] * 10, typeSplit[i + 1] * 10);
                }
                var averageC = average(subarray);
                if (averageC < - 2 * SD) {return redto2sigma(averageC);}
                    else if (averageC >= - 2 * SD && averageC < - SD) {return sigma2tosigma(averageC);}
                    else if (averageC >= - SD && averageC < 0) {return sigmatowhite(averageC);}
                    else if (averageC == 0) {return "white";}
                    else if (averageC > 0 && averageC <= SD) {return whitetosigma(averageC);}
                    else if (averageC > SD && averageC <= 2 * SD) {return sigmatosigma2(averageC);}
                    else if (averageC > 2 * SD) {return sigma2togreen(averageC);}
            })
            .attr("stroke")

    d3.select("#SvgDrugType")
        .selectAll("text")
        .data(drugType)
        .enter()
            .append("text")
            .attr("x", wType / 2)
            .attr("y", function(d,i) {
                if (i == drugType.length - 1) {
                    return (yScale(typeSplit[i + 1]) + yScale(typeSplit[i]) + yScale.rangeBand()) / 2 + hG;
                }else{
                    return (yScale(typeSplit[i]) + yScale(typeSplit[i + 1])) / 2 + hG;
                }
            })
            .attr("text-anchor", "middle")
            .attr("fill","black")
            .attr("font-size", yScale.rangeBand()/1.5 + "px")
            .text(function(d) {
                return d;
            })

    d3.select("#SvgDrugType")
        .append("text")
        .attr("x", wType / 2)
        .attr("y", hG - yScale.rangeBand() / 2 - 10)
        .attr("text-anchor", "middle")
        .attr("fill","black")
        .attr("dy",".35em")
        .attr("font-size", yScale.rangeBand()/1.5 + "px")
        .attr("font-weight", "bold")
        .text("TYPE")

    svgG.append("text")
        .attr("x", paddingW / 2)
        .attr("y", hG - yScale.rangeBand() / 2 - 10)
        .attr("text-anchor", "middle")
        .attr("fill","black")
        .attr("dy",".35em")
        .attr("font-size", yScale.rangeBand()/1.5 + "px")
        .attr("font-weight", "bold")
        .text("DRUG NAME")

    d3.select("#SvgLegend")
        .selectAll("text")
        .data(legendNumber)
        .enter()
            .append("text")
            .attr("x", wLegend / 2)
            .attr("y", function(d,i) {
                switch(i) {
                    case 0: return 10;
                    case 1: return (hG + hD) / 2;
                    case 2: return hG + hD - 10;
                }
            })
            .attr("font-weight", "bold")
            .attr("text-anchor", "middle")
            .attr("dy",".35em")
            .text(function(d) {
                return d;
            })
}

$(document).ready(function() {

	$('#URLgene').focusin(function() {
		$(this).attr ('value', '');
	})
	.focusout(function() {
		$(this).attr ('value', 'Enter your URL ...');
	})
	.keyup(function() {
		spreadsheetGene = $(this).val();
	});

	$('#URLdrug').focusin(function() {
		$(this).attr ('value', '');
	})
	.focusout(function() {
		$(this).attr ('value', 'Enter your URL ...');
	})
	.keyup(function() {
		spreadsheetDrug = $(this).val();
	});

	$('#submit').click(function() {

		$('#OriginalDataGene').sheetrock({
			url: spreadsheetGene,
			cellHandler: _parseFloat
		});

		$('#OriginalDataDrug').sheetrock({
			url: spreadsheetDrug,
            sql: "order by B",
			cellHandler: _parseFloat
		});
	});
})