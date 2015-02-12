<?php require_once('../private/include.php'); ?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $config->getProjectName(); ?></title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="<?php echo $config->getProjectDescription(); ?>" />
        <meta name="keywords" content="<?php echo $tags; ?>" />
        <meta name="author" content="pmav" />

        <link type="text/css" href="assets/css/style.css" rel="stylesheet">

        <script type="text/javascript" src="assets/syntaxhighlighter/scripts/shCore.js"></script>
        <script type="text/javascript" src="assets/syntaxhighlighter/scripts/shBrushJScript.js"></script>

        <link type="text/css" rel="stylesheet" href="assets/syntaxhighlighter/styles/shCore.css">
        <link type="text/css" rel="stylesheet" href="assets/syntaxhighlighter/styles/shThemeDefault.css">
        <script type="text/javascript">
            SyntaxHighlighter.config.clipboardSwf = 'assets/syntaxhighlighter/scripts/clipboard.swf';
            SyntaxHighlighter.all();
        </script>
    </head>
    <body>

        <div id="wrapper">
            <div id="header">
                <h1><?php echo $config->getProjectName(); ?></h1>
            </div>

            <div id="main">

                <div class="entry">

                    <div class="info">
                        <h2>Source Code</h2>

                        Download: not available.
                    </div>

                    <pre class="brush: jscript;">/**
 * Javascript N-Queens, v1.0.0
 * 
 * 2011, http://pmav.eu
 */

var NQUEENS = {

    runs : 1,
    
    plotData : [],

    load : function() {
        $.plot($(&quot;#placeholder&quot;), [[[0, 0], [1, 0]]], {
            grid: {
                backgroundColor: {
                    colors: [&quot;#fff&quot;, &quot;#eee&quot;]
                }
            }
        });
    },

    init : function(runs) {

        // Check user input.

        var validInput = true;

        var queens = parseInt(document.getElementById(&quot;queensnumber&quot;).value, 10);

        runs = parseInt(runs, 10);

        if (runs &lt; 1 || runs &gt; 100) {
            this.util.log(&quot;Invalid input!&quot;);
            validInput = false;
        }

        if (queens &lt; 4 || queens &gt; 128) {
            this.util.log(&quot;Invalid input! Use Queens from 4 to 128.&quot;);
            validInput = false;
        }

        if (validInput) {

            for (var i = 0; i &lt; runs; i++) {

                var time = new Date().getTime();
                var data = this.geneticAlgorithm.run(queens);
                time = new Date().getTime() - time;

                if (data.result) {
                    this.util.log(&quot;[Run #&quot;+NQUEENS.runs+&quot;] Solution found for &quot;+queens+&quot;-Queens after &quot;+data.lastGeneration+&quot; generations. (&quot;+time+&quot; ms, &quot;+Math.ceil((time/data.lastGeneration))+&quot; ms/gen) &quot;);
                    
                    this.util.log(this.util.generateHTMLforIndividual(data.solutionIndividual), &quot;solutions&quot;);
                    
                    this.util.log(&quot;[Run #&quot;+NQUEENS.runs+&quot;]&quot;, &quot;solutions&quot;);
                } else {
                    this.util.log(&quot;[Run #&quot;+NQUEENS.runs+&quot;] Solution NOT found for &quot;+queens+&quot;-Queens after &quot;+data.lastGeneration+&quot; generations. (&quot;+time+&quot; ms, &quot;+Math.ceil((time/data.lastGeneration))+&quot; ms/gen) &quot;);
                }

                this.generateOutput(data.fitnessValuesHistory, data.lastGeneration, &quot;Run #&quot;+NQUEENS.runs);

                NQUEENS.runs = NQUEENS.runs + 1;
            }
        }
    },


    generateOutput : function(fitnessValues, lastGeneration, label) {
        var i, statistics, currentAverage, maxFitness = -1, averageOutput = [], maximumOutput = [], minimumOutput = [];

        for (i = 1; i &lt;= lastGeneration; i++) {
            currentAverage = this.util.average(fitnessValues[i]);

            if (currentAverage &gt; maxFitness) {
                maxFitness = currentAverage;
            }
        }

        for (i = 1; i &lt;= lastGeneration; i++) {
            statistics = this.util.statistics(fitnessValues[i]);

            //maximumOutput += &quot;&lt;div class=\&quot;info\&quot;&gt;#&quot;+i+&quot;: &quot;+statistics.maximum+&quot;&lt;/div&gt; &lt;div class=\&quot;bar maximum\&quot; style=\&quot;width: &quot;+(this.util.round(statistics.maximum/maxFitness)*400)+&quot;px;\&quot;&gt;&amp;nbsp;&lt;/div&gt;&lt;div class=\&quot;cl\&quot;&gt;&amp;nbsp;&lt;/div&gt;&quot;;
            //averageOutput += &quot;&lt;div class=\&quot;info\&quot;&gt;#&quot;+i+&quot;: &quot;+this.util.round(statistics.average)+&quot;&lt;/div&gt; &lt;div class=\&quot;bar average\&quot; style=\&quot;width: &quot;+(this.util.round(statistics.average/maxFitness)*400)+&quot;px;\&quot;&gt;&amp;nbsp;&lt;/div&gt;&lt;div class=\&quot;cl\&quot;&gt;&amp;nbsp;&lt;/div&gt;&quot;;
            //minimumOutput += &quot;&lt;div class=\&quot;info\&quot;&gt;#&quot;+i+&quot;: &quot;+statistics.minimum+&quot;&lt;/div&gt; &lt;div class=\&quot;bar minimum\&quot; style=\&quot;width: &quot;+(this.util.round(statistics.minimum/maxFitness)*400)+&quot;px;\&quot;&gt;&amp;nbsp;&lt;/div&gt;&lt;div class=\&quot;cl\&quot;&gt;&amp;nbsp;&lt;/div&gt;&quot;;
			
            maximumOutput.push([i, statistics.maximum]);
            averageOutput.push([i, this.util.round(statistics.average)]);
            minimumOutput.push([i, statistics.minimum]);
        }

        if (NQUEENS.plotData.length == 5) {
            NQUEENS.plotData = NQUEENS.plotData.slice(1);
        }

        //NQUEENS.plotData.push({
        //    label: label+&quot; MAX&quot;,
        //    data: maximumOutput
        //});

        NQUEENS.plotData.push({
            label: label,
            data: averageOutput
        });

        //NQUEENS.plotData.push({
        //    label: label+&quot; MIN&quot;,
        //    data: minimumOutput
        //});

        //return &quot;&lt;h2&gt;Average Values&lt;/h2&gt;&quot;+averageOutput + &quot;&lt;h2&gt;Maximum Values&lt;/h2&gt;&quot; + maximumOutput + &quot;&lt;h2&gt;Minimum Values&lt;/h2&gt;&quot; + minimumOutput;

        $.plot($(&quot;#placeholder&quot;), NQUEENS.plotData, {
            grid: {
                backgroundColor: {
                    colors: [&quot;#fff&quot;, &quot;#eee&quot;]
                }
            }
        });
    },
    

    /**
     * Genetic Algorithm implementation.
     */
    geneticAlgorithm : {

        run : function(queensNumber) {
            var i;

            var populationSize = queensNumber * 2;
            var maxGenerations = 1000;
            var catastropheFreeGenerations = 0.10 * maxGenerations;
            var currentGeneration = 0;

            var fitnessValuesHistory = [];
            var fitnessValues = [];
            var solutionFound = false;
            var solutionGeneration = undefined;
            var solutionIndividual = undefined;

            var individuals = this.createRandomIndividuals(queensNumber, populationSize);

            this.fitness.setValues(individuals);

            for (currentGeneration = 1; currentGeneration &lt;= maxGenerations ; currentGeneration++) {

                // Catastrophe.
                if ((currentGeneration &gt; catastropheFreeGenerations) &amp;&amp; (NQUEENS.util.random(1, 100) &gt; 5)) {
                    catastropheFreeGenerations += currentGeneration + catastropheFreeGenerations;

                    individuals = this.createRandomIndividuals(queensNumber, populationSize);
                    // createIndividualFromAnother();
                }

                this.nextGeneration(individuals);

                fitnessValues = []

                for (i = 0; i &lt; individuals.length; i++) {

                    if (individuals[i].fitness === 0 &amp;&amp; solutionFound === false) {
                        solutionFound = true;
                        solutionGeneration = currentGeneration;
                        solutionIndividual = individuals[i];
                    }

                    fitnessValues[i] = individuals[i].fitness;
                }

                fitnessValuesHistory[currentGeneration] = fitnessValues;

                if (solutionFound) {
                    break;
                }
            }

            return {
                &quot;result&quot; : solutionFound,
                &quot;lastGeneration&quot; : solutionFound ? solutionGeneration : currentGeneration - 1,
                &quot;fitnessValuesHistory&quot; : fitnessValuesHistory,
                &quot;solutionIndividual&quot; : solutionIndividual
            };
        },


        /**
         * Keep top individuals and replace the others with mutations from the first ones.
         */
        nextGeneration : function(individuals) {
            var cutPercentage = 0.10;
            var numberMutations = 1;
            var i, j, cut = Math.ceil(individuals.length * cutPercentage);

            this.orderByFitnessInverse(individuals);

            for (i = cut, j = 0; i &lt; individuals.length ; i++, j++) {
                individuals[i] =  this.copyIndividual(individuals[j % cut]);
                this.mutate(individuals[i], numberMutations);
            }
        },


        /**
         * Replace the bottom half with the better half (using mutations).
         */
        nextGeneration2: function(individuals) {
            var numberMutations = 1;
            var i;

            this.orderByFitness(individuals);

            for (i = 0; i &lt; (individuals.length / 2); i++) {
                individuals[i] = this.copyIndividual(individuals[individuals.length - 1 - i]);
                this.mutate(individuals[i], numberMutations);
            }
        },

        createRandomIndividuals : function(queensNumber, populationSize) {
            var i, defaultIndividual, chromosome = [], individuals = [];

            // Create default individual: [0, 1, 2, ...].
            for (i = 0; i &lt; queensNumber; i++) {
                chromosome[i] = i;
            }

            // Default individual object.
            defaultIndividual = {
                &quot;chromosome&quot;: chromosome,
                &quot;fitness&quot;: 0.0
            };

            // Create N individuals using M mutations from the default one.
            for (i = 0; i &lt; populationSize; i++) {
                individuals[i] = this.copyIndividual(defaultIndividual)
                this.mutate(individuals[i], queensNumber);
            }

            return individuals;
        },

        createIndividualFromAnother : function(individual, numberOfMutations) {
            var newIndividual = this.copyIndividual(individual);
            
            return this.mutate(newIndividual, numberOfMutations);
        },

        copyIndividual : function(individual) {
            var newIndividual = {
                &quot;chromosome&quot;: individual.chromosome.slice(0),
                &quot;fitness&quot;: individual.fitness
            };

            return newIndividual
        },

        orderByFitnessInverse : function(individuals) {
            individuals.sort(function(a, b) {
                if (a.fitness &gt; b.fitness) {
                    return 1;
                } else if (a.fitness &lt; b.fitness) {
                    return -1;
                }

                return 0;
            });
        },

        orderByFitness : function(individuals) {
            individuals.sort(function(a, b) {
                if (a.fitness &gt; b.fitness) {
                    return -1;
                } else if (a.fitness &lt; b.fitness) {
                    return 1;
                }

                return 0;
            });
        },

        mutate : function(individual, numberOfMutations) {
            var i, value, position1, position2, individualLength = individual.chromosome.length - 1;

            for (i = 0; i &lt; numberOfMutations; i++) {
                do {
                    position1 = NQUEENS.util.random(0, individualLength);
                    position2 = NQUEENS.util.random(0, individualLength);
                } while (position1 === position2);
            
                value = individual.chromosome[position1]
                individual.chromosome[position1] = individual.chromosome[position2]
                individual.chromosome[position2] = value;
            }

            individual.fitness = this.fitness.calculate(individual.chromosome);
        },


        /**
         * Fitness algorithm.
         */
        fitness : {

            getValues : function(individuals) {
                var i, fitnessValues = [];
        
                for (i = 0; i &lt; individuals.length; i++) {
                    fitnessValues[i] = individuals[i].fitness;
                }

                return fitnessValues;
            },


            setValues : function(individuals) {
                var i;

                for (i = 0; i &lt; individuals.length; i++) {
                    individuals[i].fitness = this.calculate(individuals[i].chromosome);
                }
            },


            setValue : function(individual) {
                individual.fitness = this.calculate(individual.chromosome);
            },


            calculate : function(chromosome) {
                var i, j, m, n, hits, chromosomeLength = chromosome.length;

                hits = 0;
                for (i = 0; i &lt; chromosomeLength; i++) {
                    for (j = 0; j &lt; chromosomeLength; j++) {
                        if (i !== j) {
                            m = (chromosome[j] - chromosome[i]);
                            n = (j - i);

                            if (m == n || m == -n) {
                                hits = hits + 1;
                            }
                        }
                    }
                }

                return hits;
            }
        }
    },


    /**
     * Utils.
     */
    util : {

        generateHTMLforIndividual : function(individual) {
            if (individual === undefined) {
                return undefined;
            }

            var i, j, html = &quot;&quot;;

            html += &quot;&quot;;
            for (i = 0; i &lt; individual.chromosome.length; i++) {
                html += &quot;&lt;tr&gt;&quot;;
                for (j = 0; j &lt; individual.chromosome.length; j++) {
                    if (j === individual.chromosome[i]) {
                        html += &quot;Q&quot;;
                    } else {
                        html += &quot;.&quot;;
                    }
                }
                html += &quot;&lt;br&gt;&quot;;
            }
            html += &quot;&quot;;

            return html;
        },


        random : function(i, j) {
            return Math.round((Math.random()*(j-i))+i);
        },

        round : function(i) {
            return Math.round(i*10)/10;
        },

        statistics : function(values) {
            if (values === undefined) {
                return undefined;
            }

            var i, sum = 0.0, minimum = undefined, maximum = undefined;

            for (i = 0; i &lt; values.length; i++) {
                sum += values[i];

                if (minimum === undefined || values[i] &lt; minimum) {
                    minimum = values[i];
                }

                if (maximum === undefined || values[i] &gt; maximum) {
                    maximum = values[i];
                }
            }

            return {
                &quot;minimum&quot; : minimum,
                &quot;maximum&quot; : maximum,
                &quot;average&quot; : sum/values.length
            };
        },

        average : function(values) {
            if (values === undefined) {
                return 0;
            }

            var i, sum = 0.0;

            for (i = 0; i &lt; values.length; i++) {
                sum += values[i];
            }

            return sum/values.length;
        },

        log : function(data, id) {

            id = id || &quot;log&quot;;

            document.getElementById(id).innerHTML = data + &quot;&lt;br&gt;&quot; + document.getElementById(id).innerHTML;
        }
    }

};</pre>
                </div>

            </div>
            <div id="footer"><?php echo $config->getProjectName(); ?> | <a href="http://pmav.eu">pmav.eu</a> | <?php echo $config->getProjectDate(); ?> | <a href="http://validator.w3.org/check?uri=referer">Valid HTML 5</a> | This work is licensed under a <a rel="license" href="../assets/LICENSE">MIT License</a>.</div>
        </div>

        <script type="text/javascript">

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-284702-17']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>
    </body>
</html>
