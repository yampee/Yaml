Using Yampee YAML
==================

The Yampee YAML library is very simple and consists of two main classes: one
to parse YAML strings (`Yampee_Yaml_Parser`), and the other to dump a PHP array to
a YAML string (`Yampee_Yaml_Dumper`).

On top of these two core classes, the main `Yampee_Yaml_Yaml` class acts as a thin
wrapper and simplifies common uses.

Reading YAML Files
------------------

The `Yampee_Yaml_Parser::parse()` method parses a YAML string and converts it to a
PHP array:

    [php]
    $yaml = new Yampee_Yaml_Parser();
    $value = $yaml->parse(file_get_contents('/path/to/file.yaml'));

If an error occurs during parsing, the parser throws an exception indicating
the error type and the line in the original YAML string where the error
occurred:

    [php]
    try {
      $value = $yaml->parse(file_get_contents('/path/to/file.yaml'));
    } catch (InvalidArgumentException $e) {
      // an error occurred during parsing
      echo 'Unable to parse the YAML string: '.$e->getMessage();
    }

>**TIP**
>As the parser is reentrant, you can use the same parser object to load
>different YAML strings.

When loading a YAML file, it is sometimes better to use the `Yampee_Yaml_Yaml::load()`
wrapper method:

    [php]
    $yaml = new Yampee_Yaml_Yaml();
    $loader = $yaml->load('/path/to/file.yml');

The `Yampee_Yaml_Yaml::load()` method takes a YAML string or a file containing
YAML. Internally, it calls the `Yampee_Yaml_Parser::parse()` method, but with some
added bonuses:

  * It executes the YAML file as if it was a PHP file, so that you can embed
    PHP commands in YAML files;

  * When a file cannot be parsed, it automatically adds the file name to the
    error message, simplifying debugging when your application is loading
    several YAML files.

Writing YAML Files
------------------

The `Yampee_Yaml_Dumper` dumps any PHP array to its YAML representation:

    [php]
    $array = array('foo' => 'bar', 'bar' => array('foo' => 'bar', 'bar' => 'baz'));

    $dumper = new Yampee_Yaml_Dumper();
    $yaml = $dumper->dump($array);
    file_put_contents('/path/to/file.yaml', $yaml);

>**NOTE**
>Of course, the Yampee YAML dumper is not able to dump resources. Also,
>even if the dumper is able to dump PHP objects, it is to be considered
>an alpha feature.


The YAML format supports two kind of representation for arrays, the expanded
one, and the inline one. By default, the dumper uses the inline
representation:

    [yml]
    { foo: bar, bar: { foo: bar, bar: baz } }

The second argument of the `dump()` method customizes the level at which the
output switches from the expanded representation to the inline one:

    [php]
    echo $dumper->dump($array, 1);

-

    [yml]
    foo: bar
    bar: { foo: bar, bar: baz }

-

    [php]
    echo $dumper->dump($array, 2);

-

    [yml]
    foo: bar
    bar:
      foo: bar
      bar: baz
