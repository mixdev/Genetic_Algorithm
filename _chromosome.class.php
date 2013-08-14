<?php

/*

Read this first
http://burakkanber.com/blog/machine-learning-genetic-algorithms-part-1-javascript/

Then this
http://www.codeproject.com/Articles/26203/Genetic-Algorithm-Library

Free ebook
http://www.gp-field-guide.org.uk/

http://www.informatics.indiana.edu/fil/CAS/PPT/Davis/sld008.htm


GA applications. Great book
http://www.intechopen.com/books/genetic-algorithms-in-applications/portfolio-management-using-artificial-trading-systems-based-on-technical-analysis

Another one
http://www.intechopen.com/books/bio-inspired-computational-algorithms-and-their-applications
*/


Abstract class AbstractChromosome
{
	public $id;
	
	
	/* The mating function takes another chromosome as an argument, finds the center point, and returns an array of two new children. */
	/* Also called crossover in many textbooks */
	/* http://en.wikipedia.org/wiki/Crossover_(genetic_algorithm) */
	abstract public function mate($chromosome2);

	/* the logic for returning a random chromosome depends on the type. For example, helloworld is constructiong an n-length string. While click prediction may need to create a mix of time, gender, creative combination. So this is abstract */
	abstract public function random();

	abstract public function fitness();

	
	/* The mutate method takes a float as an argument — the percent chance that the chromosome will mutate */
	/* The mating probability is usually high [about 80%], and the mutation probability should be relatively
	low [about 3%, but for some problems, a higher probability gives better results]. A higher mutation 
	probability can turn the genetic algorithm in to a random search algorithm. 
	http://www.codeproject.com/Articles/26203/Genetic-Algorithm-Library */
	public function mutate($chance)
	{
		//echo(88888 );
		if($chance < (mt_rand(0,100)/100) ) return (0);
// echo(mt_rand());

		return $this->mutate();
	}
}


Abstract class AbstractPopulation
{
	public $generation = 0; 
	public $chromosomes = array();
	public $goal;
		
	/* The Population class constructor takes the target string and population size as arguments, then fills the population with random chromosomes. It also takes an chromosome object type to clone $size times */
	function __construct($goal, $size, $objtype)
	{
		$this->goal = $goal;
		$this->size = $size;
		while($size--){
			$chromosome = new $objtype ; 
			$chromosome->random();
			$chromosome->fitness();
			$this->chromosomes[] = $chromosome;
		}
		$this->sortit();
		$size = $this->size;

		/* only mating the top two chromosomes (with highest fitness). This doesn’t have to be your approach. */
		//$children = $this->chromosomes[0]->mate($this->chromosomes[1]);

		/* replace the last two chromos with the 2 new child chromosomes - for the next generation */
		//array_splice($this->chromosomes, count($this->chromosomes) - 2 , 2,  $children);
			 
		
	}

	/* this should sort the chromosomes according to your implementation logic's fitness function (not cost function - because sometime we are not sure if 0 is the least cost) */
	protected function sortit(){
		
		//sort the chromos' order according to their individual fitness DESC
		usort($this->chromosomes , array($this, "cmp")) ; //will this work? for objects, this is the format
		 
	}
	
	/* the helper function for the above sort http://www.php.net/manual/en/function.usort.php */
	/* if this doesnt work, check the comment on 04-Dec-2011 08:58 over the above URL and define it inline */
	/* no. this works :-) */
	static function cmp($a, $b)
	{
		$af = $a->fitness();
		$bf = $b->fitness();

		 //print("\n $af  $bf");
 
		if ($af == $bf){
			return 0;
		}
		return ($af < $bf) ? -1 : 1; 
	}


	public function generate()
	{	$j= -1;
		$goalreached = false;
		$c	=	count($this->chromosomes);
		$leastcost = 100000; //pass it for the first time
		while( $j++ < 5000 && $goalreached == false){ //max generations = 1000

			$this->sortit();
			$this->printit();
			$this->generation++;

			//mate 80% of the time or is a better generation
			if(mt_rand(0,10)<9 || $leastcost > $this->chromosomes[0]->fitness()){ 
				// take 2 most suitable chromosomes to mate and produce 2 children
				$children = $this->chromosomes[0]->mate($this->chromosomes[1]);
				
				/* kill & replace the last two chromos with the 2 children - for the next generation */
				array_splice($this->chromosomes, count($this->chromosomes) - 2 , 2,  $children);
				$this->sortit();

				$leastcost = $this->chromosomes[0]->fitness();
			}
			
			$i = -1; $fitness=0;			
			while($i++ < $c-1 && $goalreached == false){				
				$this->chromosomes[$i]->mutate(0.2345);
				$fitness = $this->chromosomes[$i]->fitness();
				//print("\n id: ".$this->chromosomes[$i]->id. ' - '. $fitness );				
				if($fitness <= $this->goal) {$goalreached = true;	echo("\n goal reached $fitness  $this->goal -".	$this->chromosomes[$i]->id."-");}			
			}


			


			//50ms delay just to make the scrolling readable. 
			print("\n\n");usleep(50000);
			
		}
	}


	public function printit()
	{
		print("\n".'===== Generation '.$this->generation.' ======='."\n\n");

		foreach($this->chromosomes as $chromosome){
			echo("\n".'id: '.$chromosome->id. ' - '. $chromosome->fitness() .' - ' );
		}
	}

}

