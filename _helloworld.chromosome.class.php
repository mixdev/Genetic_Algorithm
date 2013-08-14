<?php

require '_chromosome.class.php';

/*

here we will try to write a gen algo to match the string "Hello World" with randomly gererating the string and matching

*/


class HelloWorldChromosome extends AbstractChromosome
{
	var $thestring = 'Hello world';
	/* The mating function takes another chromosome as an argument, finds the center point, and returns an array of two new children. */
	
	public function mate($chromosome2){
		$c = strlen($this->id)-1;

		/* one point crossover. converges at 2k generations at 0.345* (2/3) mutation rate */
		$pivot = round(strlen($this->id)/2)-1;
		
		// so slice the chromosome to 4 parts and copy them around - four point crosover
		//$pivot=mt_rand($pivot-($pivot/2),$pivot+ ($pivot/2));
		
		//very flexible pivot. converges in less than 1.5k generations when mutation rate is 0.345* (2/3)
		$pivot=mt_rand(1,$c-1);

		$i= -1;		 
		$child1 = new HelloWorldChromosome;
		$child2 = new HelloWorldChromosome;
		//while($i++ < $c ){
			 
			/* these two didn't work (didnt converge after 40k generations) because we arnt copying features. We are averaging features */
			//$child1->id .= chr(ceil((ord($this->id[$i])+ord($chromosome2->id[$i]))    /2  ));//tendency to go up
			//$child2->id .= chr(floor((ord($this->id[$i])+ord($chromosome2->id[$i]))    /2  ));//go down
			
			 $child1->id = substr($this->id, 0, $pivot) . substr($chromosome2->id, $pivot);
			 $child2->id = substr($chromosome2->id, 0, $pivot) . substr($this->id, $pivot);
			
			//$child1->id = $this->thestring;
			//$child2->id = $this->thestring;

			 //print("\n $pivot" );
			//print("<br/>");
		//}
		
		$child1->fitness();
		$child2->fitness();
		return array($child1, $child2);
	}

	public function fitness(){
		$s = $this->thestring;
		$c = strlen($s)-1;
		$i= -1;
		$healthiness = 0;
		//$tid = ')-(+';
		while($i++ < $c ){
			 $healthiness += abs(ord($this->id[$i]) - ord($s[$i]));//sum of difference in ascii values
			 //$healthiness += abs(($s[$i]));//sum of difference in ascii values
			//print($s[$i]);
			//print("<br/>");
		}
		$this->fitness = $healthiness;
		return $this->fitness;
		// print("$this->fitness <br/>  ");
	}

	
	/* The mutate method takes a float as an argument — the percent chance that the chromosome will mutate */
	public function mutate($chance)
	{	

		//echo("cccccccccc".$chance."VVVVVVVVVV");
		if($chance < (mt_rand(0,100)/100) ) return (0);
		$s = $this->thestring;
		$c = strlen($s)-1;
		$i= -1;
		while($i++ < $c ){

			//$variations = shuffle(array(1,-1));//increase or decrease the ascii value randomly
		// print($variation);
			 $this->id[$i] = chr(mt_rand(-1,1) + ord($this->id[$i])); 
			//print($s[$i]);			 
		}




		return 1;
	}

	public function random()
	{
		$s = $this->thestring;
		$c = strlen($s)-1;
		$i= -1;
		while($i++ < $c ){
			 $this->id .= (chr(rand(32,126)));//32 is space, 126 is tilde ~

				//$this->id .= chr(rand(48,57));//just numbers
			//print($s[$i]);			 
		}
		//echo("\n". $this->id ."-- $c");
	}
}


class HelloWorldPopulation extends AbstractPopulation
{
	


}

