<?php require_once('private/include.php'); ?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $config->getProjectName(); ?></title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="<?php echo $config->getProjectDescription(); ?>" />
        <meta name="keywords" content="<?php echo $tags; ?>" />
        <meta name="author" content="pmav" />

        <link type="text/css" href="assets/css/style.css" rel="stylesheet">

        <!-- N-Queens -->
        <link rel="stylesheet" type="text/css" href="nqueens/assets/css/style.css" />
        <script type="text/javascript" src="nqueens/assets/js/javascript-nqueens.js"></script>

        <!--[if IE]><script language="javascript" type="text/javascript" src="nqueens/assets/js/flot/excanvas.min.js"></script><![endif]-->
        <script type="text/javascript" src="nqueens/assets/js/flot/jquery.min.js"></script>
        <script type="text/javascript" src="nqueens/assets/js/flot/jquery.flot.min.js"></script>

    </head>
    <body onload="NQUEENS.load();">

        <div id="wrapper">
            <div id="header">
                <h1><?php echo $config->getProjectName(); ?></h1>
            </div>

            <div id="main">

                <div class="box">

                    <!-- Intro -->

                    <p>This page presents a Javascript program that solves the <a href="http://en.wikipedia.org/wiki/Eight_queens_puzzle" title="Eight Queens Puzzle at Wikipedia">N-Queens</a> problem using a very simple <a href="http://en.wikipedia.org/wiki/Genetic_algorithm" title="Genetic Algoritm at Wikipedia">Genetic Algorithm</a>.</p><br>
    
                    Genetic Algorithm pseudocode:
                    <pre>
1) Create random individuals and compute fitness value.

2) For each generation:
   2.1) Catastrophe? (first 10% of generations are catastrophe-free; catastrophe probability: 5%)
        2.1.1) Remove all individuals.
        2.1.2) Create random individuals and compute fitness value.

    2.2) Keep top 10% individuals (based on fitness) and remove the others.

    2.3) Create new individuals by mutating the remaning (swap Queen positions).

    2.4) For each individual:
         2.4.1) Compute new fitness value based on the number of collisions between Queens.
         2.4.2) Fitness value is zero? (no collisions)
                2.4.2.1) Solution Found.</pre>

                        
                    <p>Each individual is a group of <code>(x,y)</code> coordinates that represent the position of each Queen on the board. Individual <code>1324</code> means that the Queens positions are <code>(1,1) (2,3) (3,2) (4,4)</code>.</p>
<pre>
   <strong>1 2 3 4 (x)</strong>
 <strong>1</strong> Q . . .
 <strong>2</strong> . . Q .
 <strong>3</strong> . Q . .
 <strong>4</strong> . . . Q
<strong>(y)</strong></pre>
                        
                </div>

                <!-- Groups -->

                <div  class="box">
                    <h2>Run</h2>



                    <form>
                        <span>Queens: </span> <input type="text" value="32" class="input" style="width: 30px;" id="queensnumber" />

                        <input type="button" class="submit" value="Run Once" onclick="NQUEENS.init(1);" />

                        <input type="button" class="submit" value="Run 5 Times" onclick="NQUEENS.init(5);" />
                    </form>

                    <div class="cl">&nbsp;</div>



                    <h4>Results</h4>
                    <div id="log" class="console" style="height: 100px;"></div>

                    <h4>Plot</h4>
                    <div id="placeholder" style="width:786px;height:300px;"></div>


                    <p style="text-align: center; color: #666;"><strong>Y Axis:</strong> score (lower is better), <strong>X Axis:</strong> Generation Number.</p>

                    <h4>Solutions</h4>
                    <div id="solutions" class="console" style="height: 200px;"></div>

                </div>

                <!-- Source -->

                <div class="box">
                    <h2>Source Code</h2>
                    <p>All the source code is online <a href="source/" title="Source Code">here</a>.</p>
                </div>

				<!-- Discussion -->

                <div class="box">
                    <h2>Discussion</h2>

					<div id="disqus_thread"></div> <script type="text/javascript"> var disqus_shortname = 'pmav'; var disqus_identifier = '<?php echo $config->getProjectName(); ?>'; (function() { var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true; dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js'; (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq); })();</script>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
                </div>
								
            </div>
            <div id="footer"><?php echo $config->getProjectName(); ?> | <a href="http://pmav.eu">pmav.eu</a> | <?php echo $config->getProjectDate(); ?> | <a href="http://validator.w3.org/check?uri=referer">Valid HTML 5</a> | This work is licensed under a <a rel="license" href="assets/LICENSE">MIT License</a>.</div>
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
