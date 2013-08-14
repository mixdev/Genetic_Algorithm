<pre><?php
/*
 
 $a = '1234';
 $b = ')-(+'; //33
 $c=strlen($a)-1;
 $i=-1;
$healthiness = 0;
//echo(strlen($a));
while($i++ < $c ){
 $healthiness += abs(ord($a[$i])-ord($b[$i]));
 echo("\n". $healthiness );
 
 }


exit; */

require '_helloworld.chromosome.class.php';

//i figured that for helloworld example, having 200pop siz has no effect while pop size 12 always converged before 5k :-)

$pop = new HelloWorldPopulation(0, 12, 'HelloWorldChromosome');

$pop->generate();
$pop->printit();