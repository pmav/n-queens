/**
 * Javascript N-Queens, v1.0.0
 * 
 * 2011, http://pmav.eu
 */

var NQUEENS = {

    runs : 1,
    
    plotData : [],

    load : function() {
        $.plot($("#placeholder"), [[[0, 0], [1, 0]]], {
            grid: {
                backgroundColor: {
                    colors: ["#fff", "#eee"]
                }
            }
        });
    },

    init : function(runs) {

        // Check user input.

        var validInput = true;

        var queens = parseInt(document.getElementById("queensnumber").value, 10);

        runs = parseInt(runs, 10);

        if (runs < 1 || runs > 100) {
            this.util.log("Invalid input!");
            validInput = false;
        }

        if (queens < 4 || queens > 128) {
            this.util.log("Invalid input! Use Queens from 4 to 128.");
            validInput = false;
        }

        if (validInput) {

            for (var i = 0; i < runs; i++) {

                var time = new Date().getTime();
                var data = this.geneticAlgorithm.run(queens);
                time = new Date().getTime() - time;

                if (data.result) {
                    this.util.log("[Run #"+NQUEENS.runs+"] Solution found for "+queens+"-Queens after "+data.lastGeneration+" generations. ("+time+" ms, "+Math.ceil((time/data.lastGeneration))+" ms/gen) ");
                    
                    this.util.log(this.util.generateHTMLforIndividual(data.solutionIndividual), "solutions");
                    
                    this.util.log("[Run #"+NQUEENS.runs+"]", "solutions");
                } else {
                    this.util.log("[Run #"+NQUEENS.runs+"] Solution NOT found for "+queens+"-Queens after "+data.lastGeneration+" generations. ("+time+" ms, "+Math.ceil((time/data.lastGeneration))+" ms/gen) ");
                }

                this.generateOutput(data.fitnessValuesHistory, data.lastGeneration, "Run #"+NQUEENS.runs);

                NQUEENS.runs = NQUEENS.runs + 1;
            }
        }
    },


    generateOutput : function(fitnessValues, lastGeneration, label) {
        var i, statistics, currentAverage, maxFitness = -1, averageOutput = [], maximumOutput = [], minimumOutput = [];

        for (i = 1; i <= lastGeneration; i++) {
            currentAverage = this.util.average(fitnessValues[i]);

            if (currentAverage > maxFitness) {
                maxFitness = currentAverage;
            }
        }

        for (i = 1; i <= lastGeneration; i++) {
            statistics = this.util.statistics(fitnessValues[i]);

            //maximumOutput += "<div class=\"info\">#"+i+": "+statistics.maximum+"</div> <div class=\"bar maximum\" style=\"width: "+(this.util.round(statistics.maximum/maxFitness)*400)+"px;\">&nbsp;</div><div class=\"cl\">&nbsp;</div>";
            //averageOutput += "<div class=\"info\">#"+i+": "+this.util.round(statistics.average)+"</div> <div class=\"bar average\" style=\"width: "+(this.util.round(statistics.average/maxFitness)*400)+"px;\">&nbsp;</div><div class=\"cl\">&nbsp;</div>";
            //minimumOutput += "<div class=\"info\">#"+i+": "+statistics.minimum+"</div> <div class=\"bar minimum\" style=\"width: "+(this.util.round(statistics.minimum/maxFitness)*400)+"px;\">&nbsp;</div><div class=\"cl\">&nbsp;</div>";
			
            maximumOutput.push([i, statistics.maximum]);
            averageOutput.push([i, this.util.round(statistics.average)]);
            minimumOutput.push([i, statistics.minimum]);
        }

        if (NQUEENS.plotData.length == 5) {
            NQUEENS.plotData = NQUEENS.plotData.slice(1);
        }

        //NQUEENS.plotData.push({
        //    label: label+" MAX",
        //    data: maximumOutput
        //});

        NQUEENS.plotData.push({
            label: label,
            data: averageOutput
        });

        //NQUEENS.plotData.push({
        //    label: label+" MIN",
        //    data: minimumOutput
        //});

        //return "<h2>Average Values</h2>"+averageOutput + "<h2>Maximum Values</h2>" + maximumOutput + "<h2>Minimum Values</h2>" + minimumOutput;

        $.plot($("#placeholder"), NQUEENS.plotData, {
            grid: {
                backgroundColor: {
                    colors: ["#fff", "#eee"]
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

            for (currentGeneration = 1; currentGeneration <= maxGenerations ; currentGeneration++) {

                // Catastrophe.
                if ((currentGeneration > catastropheFreeGenerations) && (NQUEENS.util.random(1, 100) > 5)) {
                    catastropheFreeGenerations += currentGeneration + catastropheFreeGenerations;

                    individuals = this.createRandomIndividuals(queensNumber, populationSize);
                    // createIndividualFromAnother();
                }

                this.nextGeneration(individuals);

                fitnessValues = []

                for (i = 0; i < individuals.length; i++) {

                    if (individuals[i].fitness === 0 && solutionFound === false) {
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
                "result" : solutionFound,
                "lastGeneration" : solutionFound ? solutionGeneration : currentGeneration - 1,
                "fitnessValuesHistory" : fitnessValuesHistory,
                "solutionIndividual" : solutionIndividual
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

            for (i = cut, j = 0; i < individuals.length ; i++, j++) {
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

            for (i = 0; i < (individuals.length / 2); i++) {
                individuals[i] = this.copyIndividual(individuals[individuals.length - 1 - i]);
                this.mutate(individuals[i], numberMutations);
            }
        },

        createRandomIndividuals : function(queensNumber, populationSize) {
            var i, defaultIndividual, chromosome = [], individuals = [];

            // Create default individual: [0, 1, 2, ...].
            for (i = 0; i < queensNumber; i++) {
                chromosome[i] = i;
            }

            // Default individual object.
            defaultIndividual = {
                "chromosome": chromosome,
                "fitness": 0.0
            };

            // Create N individuals using M mutations from the default one.
            for (i = 0; i < populationSize; i++) {
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
                "chromosome": individual.chromosome.slice(0),
                "fitness": individual.fitness
            };

            return newIndividual
        },

        orderByFitnessInverse : function(individuals) {
            individuals.sort(function(a, b) {
                if (a.fitness > b.fitness) {
                    return 1;
                } else if (a.fitness < b.fitness) {
                    return -1;
                }

                return 0;
            });
        },

        orderByFitness : function(individuals) {
            individuals.sort(function(a, b) {
                if (a.fitness > b.fitness) {
                    return -1;
                } else if (a.fitness < b.fitness) {
                    return 1;
                }

                return 0;
            });
        },

        mutate : function(individual, numberOfMutations) {
            var i, value, position1, position2, individualLength = individual.chromosome.length - 1;

            for (i = 0; i < numberOfMutations; i++) {
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
        
                for (i = 0; i < individuals.length; i++) {
                    fitnessValues[i] = individuals[i].fitness;
                }

                return fitnessValues;
            },


            setValues : function(individuals) {
                var i;

                for (i = 0; i < individuals.length; i++) {
                    individuals[i].fitness = this.calculate(individuals[i].chromosome);
                }
            },


            setValue : function(individual) {
                individual.fitness = this.calculate(individual.chromosome);
            },


            calculate : function(chromosome) {
                var i, j, m, n, hits, chromosomeLength = chromosome.length;

                hits = 0;
                for (i = 0; i < chromosomeLength; i++) {
                    for (j = 0; j < chromosomeLength; j++) {
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

            var i, j, html = "";

            html += "";
            for (i = 0; i < individual.chromosome.length; i++) {
                html += "<tr>";
                for (j = 0; j < individual.chromosome.length; j++) {
                    if (j === individual.chromosome[i]) {
                        html += "Q";
                    } else {
                        html += ".";
                    }
                }
                html += "<br>";
            }
            html += "";

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

            for (i = 0; i < values.length; i++) {
                sum += values[i];

                if (minimum === undefined || values[i] < minimum) {
                    minimum = values[i];
                }

                if (maximum === undefined || values[i] > maximum) {
                    maximum = values[i];
                }
            }

            return {
                "minimum" : minimum,
                "maximum" : maximum,
                "average" : sum/values.length
            };
        },

        average : function(values) {
            if (values === undefined) {
                return 0;
            }

            var i, sum = 0.0;

            for (i = 0; i < values.length; i++) {
                sum += values[i];
            }

            return sum/values.length;
        },

        log : function(data, id) {

            id = id || "log";

            document.getElementById(id).innerHTML = data + "<br>" + document.getElementById(id).innerHTML;
        }
    }

};