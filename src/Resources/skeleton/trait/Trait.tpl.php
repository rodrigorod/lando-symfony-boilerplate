<?php echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use <?php echo $use_statements; ?>;

/**
* Trait <?php echo $trait_name; ?>.
*/
trait <?php echo $trait_name;"\n"; ?>
{
    /**
    * <?php echo $dependency; ?>.
    */
    protected <?php echo $dependency; ?> <?php echo '$'.$property_name; ?>;

    /**
    * Set <?php echo $dependency; ?>.
    *
    * @param <?php echo $dependency; ?> <?php echo '$'.$property_name."\n"; ?>
    *  <?php echo $dependency."\n"; ?>
    *
    * @required
    */
    public function set<?php echo ucwords($property_name); ?>(<?php echo $dependency; ?> <?php echo '$'.$property_name; ?>): void
    {
        $this-><?php echo $property_name; ?> = <?php echo '$'.$property_name; ?>;
    }
}
